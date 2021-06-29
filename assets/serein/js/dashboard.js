// (function($) {
//   'use strict';
//   $(function() {

//     if ($("#dashboard-monthly-analytics").length) {
//       var ctx = document.getElementById('dashboard-monthly-analytics').getContext("2d");
//       var myChart = new Chart(ctx, {
//         type: 'line',
//         data: {
//           labels: ['Jan', 'Feb', 'Mar', 'Arl', 'May', 'Jun', 'Jul', 'Aug'],
//           datasets: [{
//               label: "Ios",
//               borderColor: '#f2a654',
//               backgroundColor: '#f2a654',
//               pointRadius: 0,
//               fill: true,
//               borderWidth: 1,
//               fill: 'origin',
//               data: [0, 0, 30000000, 0, 0, 0, 50, 0]
//             },
//             // {
//             //   label: "Android",
//             //   borderColor: 'rgba(235, 105, 143, .9)',
//             //   backgroundColor: 'rgba(235, 105, 143, .9)',
//             //   pointRadius: 0,
//             //   fill: true,
//             //   borderWidth: 1,
//             //   fill: 'origin',
//             //   data: [0, 35, 0, 0, 30, 0, 0, 0]
//             // },
//             // {
//             //   label: "Windows",
//             //   borderColor: 'rgba(241, 155, 84, .8)',
//             //   backgroundColor: 'rgba(241, 155, 84, .8)',
//             //   pointRadius: 0,
//             //   fill: true,
//             //   borderWidth: 1,
//             //   fill: 'origin',
//             //   data: [0, 0, 0, 40, 10, 50, 0, 0]
//             // }
//           ]
//         },
//         options: {
//           maintainAspectRatio: false,
//           legend: {
//             display: false,
//             position: "top"
//           },
//           scales: {
//             xAxes: [{
//               ticks: {
//                 display: true,
//                 beginAtZero: true,
//                 fontColor: '#696969'
//               },
//               gridLines: {
//                 display: false,
//                 drawBorder: false,
//                 color: 'transparent',
//                 zeroLineColor: '#eeeeee'
//               }
//             }],
//             yAxes: [{
//               gridLines: {
//                 drawBorder: false,
//                 display: true,
//                 color: '#b8b8b8',
//               },
//               categoryPercentage: 0.5,
//               ticks: {
//                 display: true,
//                 beginAtZero: true,
//                 stepSize: 20000000,
//                 max: 80000000,
//                 fontColor: '#696969'
//               }
//             }]
//           },
//         },
//         elements: {
//           point: {
//             radius: 0
//           }
//         }
//       });
//       document.getElementById('js-legend').innerHTML = myChart.generateLegend();
//     }
    
//   });
// })(jQuery);