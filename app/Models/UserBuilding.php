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

	// Informações base do edificio
	public function base()
	{
		return $this->belongsTo('App\Models\Building', 'building_id', 'id');
	}

	// Status do edificio
	public function status()
	{
		return $this->belongsTo('App\Models\BuildingStatus', 'building_status_id', 'id');
	}

	// Dono do edificio
	public function user()
	{
		return $this->belongsTo('App\Models\User');
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
		return !$this->name ? $this->base->name : $this->name;
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
		return	$this->level < config('game.max_build_level') &&
				$this->user->currency >= $this->base->upgrade_cost;
	}

	public function upgradeText()
	{
		return '<b>'. __('Cost') . ':</b> ' . currency($this->base->upgrade_cost);
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
		return $this->update([
			'earnings'		=> $this->earnings + $earning,
			'last_claim'	=> $earning,
			'last_claim_at'	=> Carbon::now(),
		]);
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
		return round($percentage > 100 ? 100 : $percentage, 2);
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

	public function repairText()
	{
		$cost	= $this->repairCost();

		return '<b>'. __('Cost') . ':</b> ' . currency($cost);
	}
}
