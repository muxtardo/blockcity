require('./bootstrap');

const Welcome = require('./Pages/welcome').default;

const pages = {
    welcome: [Welcome],
};

console.log(pages,current_page, pages[current_page]);
pages?.[current_page]?.forEach(fn => {
    fn();
});