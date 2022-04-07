require('./app.scss');

// Include and define jQuery
const $ = require('jquery');
global.$ = global.jQuery = $;
require('jquery-ui');

// Require toastr and make it global
const toastr = require('toastr');
global.toastr = toastr;

const moment = require('moment/moment');
global.moment = moment;

require('bootstrap');
require('admin-lte/dist/js/adminlte.min');
require('admin-lte/plugins/chart.js/Chart.bundle.min');
require('summernote');
require('tempusdominus-bootstrap-4');
require('tempusdominus-core');
require('moment-timezone');
require('popper.js');