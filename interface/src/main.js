import routes from './routes'
import guide from 'fivemetrics/utils/Guide'
import render from '@sketchpixy/rubix/lib/node/router';

window.HOST = ''
if (process && process.env.NODE_ENV == 'development') {
  window.HOST = '//' + window.location.hostname + ':8000/index.php'
}

if (process && process.env.PAGE == 'landing') {
    window.HOST = '//app.fivemetrics.io'
}

render(routes, () => {
  //console.log('Routes Completed rendering!');
});

if (module.hot) {
  module.hot.accept('./routes', () => {
    // reload routes again
    require('./routes').default;
    render(routes);
  });
}

(function(w) {

    if (w) {
        let timer;
        w.addEventListener("resize", function () {
            clearTimeout(timer)
            timer = setTimeout(PubSub.publish.bind(PubSub), 1000, "windowResize", {})
        })
    }

}) (window)
