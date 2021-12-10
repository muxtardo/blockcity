<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBuilding extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_id',
        'building_id',
        'building_status_id',
        'highlight',
        'level',
		'image',
        'earnings',
        'last_claim',
        'last_claim_at',
    ];

	protected $casts = [
		'last_claim_at' => 'datetime',
	];

	// Informações base do edificio
	public function base()
	{
		return $this->belongsTo(Building::class, 'building_id', 'id');
	}

	// Status do edificio
	public function status()
	{
		return $this->belongsTo(BuildingStatus::class, 'building_status_id', 'id');
	}

	// Dono do edificio
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	// Rendimento do edificio
	public function getIncomes()
	{
		$status		= $this->status;
		$incomes	= $this->base->incomes;
		if ($status->loss) {
			$incomes	-= $incomes * ($status->loss / 100);
		}
		return $incomes * $this->level;
	}

	// Tras o nome do edificio
	public function getName()
	{
		return !$this->name ? $this->base->name : trim($this->name);
	}

	// Retorna a imagem do edificio
	public function getImage($path_only = false)
	{
		$path	= asset('assets/images/buildings/' . $this->base->rarity . '/' . $this->image . '.png');
		if (!$path_only)
		{
			return '<img src="' . $path . '" alt="' . $this->getName() . '" class="img-fluid" />';
		}

		return $path;
	}

	// É um prédio novo?
	public function isNew()
	{
		$highlight	= $this->highlight;
		if ($highlight) {
			$this->update([ 'highlight' => false ]);
		}

		return $highlight;
	}

	// faz o upgrade de level ddo edificio
	public function upgrade()
	{
		return $this->update([ 'level' => $this->level + 1 ]);
	}

	// Pode fazer upgrade?
	public function canUpgrade()
	{
		return	$this->level < config('game.max_build_level');
	}

	// Pode fazer upgrade?
	public function canClaim()
	{
		return	$this->progressClaim() >= config('game.min_claim');
	}

	// Coleta o rendimento do prédio
	public function claim()
	{
		// Earnings
		$earning	= $this->availableClaim();

		// Update user building claim
		if ($this->update([
			'earnings'		=> $this->earnings + $earning,
			'last_claim'	=> $earning,
			'last_claim_at'	=> Carbon::now(),
		])) {
			return $earning;
		}

		return false;
	}

	// Progresso do rendimento
	public function progressClaim()
	{
		$date1 = Carbon::parse($this->last_claim_at)->timestamp;
		$date2 = Carbon::parse($this->last_claim_at)->addDay()->timestamp;
		$today = Carbon::now()->timestamp;

		$dateDiff			= $date2 - $date1;
		$dateDiffForToday	= $today - $date1;

		$percentage			= percent($dateDiffForToday, $dateDiff);
		return round($percentage, 2);
	}

	// Enchendo linguiça
	public function progressColor()
	{
		$percent	= $this->progressClaim();
		if ($percent < 30) {
			return 'bg-warning';
		} elseif ($percent >= 30 && $percent < 100) {
			return 'bg-primary';
		}

		return 'bg-success';
	}

	// Ganhos disponiveis para coleta,
	// porém só pode coletar quando atingir o minimo definido em configuração
	public function availableClaim()
	{
		$percent			= $this->progressClaim() / 100;
		$incomes			= $this->getIncomes();

		return $incomes * $percent;
	}

	public function needRepair()
	{
		return $this->status->loss > 0;
	}

	public function repairCost()
	{
		$status		= $this->status;
		$incomes	= $this->base->incomes;

		return $status->loss ? percentf($incomes, $status->loss) : 0;
	}

	public function repair()
	{
		$this->building_status_id = BuildingStatus::where('loss', 0)->first()->id;
		if ($this->save())
		{
			$this->status->loss = 0;
			return $this;
		}

		return false;
	}

	public function publicData()
	{
		return [
			'id'		=> $this->id,
			'name'		=> $this->getName(),
			'image'		=> $this->getImage(true),
			'rarity'	=> $this->base->rarity,
			'level'		=> $this->level,
			'highlight'	=> $this->isNew(),
			'upgrade'	=> $this->canUpgrade() ? currency($this->base->upgrade_cost) : false,
			'status'	=> [
				'repair'	=> $this->needRepair(),
				'color'		=> $this->status->color,
				'name'		=> $this->status->name,
				'loss'		=> $this->status->loss,
				'cost'		=> currency($this->repairCost())
			],
			'claim'		=> [
				'enabled'	=> $this->canClaim(),
				'color' 	=> $this->progressColor(),
				'progress'	=> $this->progressClaim(),
				'available'	=> currency($this->availableClaim()),
			],
			'stats'		=> [
				'daily'		=> currency($this->getIncomes()),
				'last'		=> currency($this->last_claim),
				'total'		=> currency($this->earnings)
			],
			'created_at'	=> $this->created_at,
			'updated_at'	=> $this->updated_at
		];

	}
}
