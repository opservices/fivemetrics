var fs = require('fs');
var ncp = require('ncp');
var pug = require('pug');
var path = require('path');
var rimraf = require('rimraf');

var publicPath = path.join(process.cwd(), 'public');
var distPath = process.env.DIST_PATH || path.join(process.cwd(), 'dist');
var indexPath = path.join(process.cwd(), 'views', 'index.pug');
var destIndexPath = path.join(distPath, 'index.html');
//var destIndexRTLPath = path.join(distPath, 'index-rtl.html');

var version = process.env.APP_VERSION || "0.1.0";

module.exports = function(callback) {
  rimraf(distPath, function() {
    fs.mkdirSync(distPath);

    ncp(publicPath, distPath, function(err) {
      if (err) {
        return console.error(err);
      }

      console.log(process.env.PAGE,process.env.NODE_ENV);

      var filesMeta = {
        "landing": {
          scripts: [ "/js/perfect-scrollbar.js", "/js/modernizr.js", "/js/app.js" ],
          stylesheets: [ "/css/main.css" ],
          paceScripts: []
        },
        "default": {
          scripts: [ "/js/plugins.js", "/js/app.js" ],
          stylesheets: [ "/css/main-blessed1.css", "/css/main-blessed2.css", "/css/main.css" ],
          paceScripts: [ "/css/pace.css", "/js/vendor/PACE/pace.min.js" ]
        }
      }

      var files = filesMeta[process.env.PAGE] || filesMeta["default"];

      var obj = {
        app_scripts: createSriptTags(files.scripts),
        app_stylesheets: createStylesheetTags(files.stylesheets),
        pace_scripts: createPaceTags(files.paceScripts)
      };

      var fn = pug.compileFile(indexPath, {
        pretty: true
      });

      var html = fn(obj)

      function createSriptTags(xs,props='') {

        return xs.reduce((acc, script) => acc.concat("\n").concat(`<script ${props} src="${script}?v=${version}"></script>`), "\n");
      }

      function createStylesheetTags(xs) {
        return xs.reduce((acc, link) => acc.concat("\n").concat(`<link rel='stylesheet' href="${link}?v=${version}" />`), "\n");
      }

      function createPaceTags([css, js]) {
        return (!css || !js)
          ? ""
          : createStylesheetTags([css]).concat(createSriptTags([js],'data-pace-options=\'{ "ajax": false }\''));
      }

      //var html = fn({
      //  app_scripts: "\n    <script src='/js/plugins.js'></script>\n    <script src='/js/app.js'></script>",
      //  app_stylesheets: "\n    <link rel='stylesheet' href='/css/main.css' />"
      //});

      //var htmlRTL = fn({
      //  app_scripts: "\n    <script src='/js/plugins.js'></script>\n    <script src='/js/app.js'></script>",
      //  app_stylesheets: "\n    <link rel='stylesheet' href='/css/main-rtl.css' />"
      //});

      fs.writeFileSync(destIndexPath, html + '\n');
      //fs.writeFileSync(destIndexRTLPath, htmlRTL + '\n');

      console.log('Done!');

      if (callback) callback();
    })
  });
};
