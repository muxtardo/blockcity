require('./bootstrap');

import Welcome from './modules/welcome';
import Dashboard from './modules/dashboard';
import Exchange from './modules/exchange';
// const Welcome = require('./modules/welcome').default;

const modules = {
    welcome: [Welcome],
	dashboard: [Dashboard],
	exchange: [Exchange],
};

// console.log(modules,current_page, modules[current_page]);
modules?.[current_page]?.forEach(fn => {
    fn();
});
