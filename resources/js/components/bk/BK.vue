
<!-- ***********************************BK from PageBody.vue************************** -->

// InputErrInfo: function(err, old){
//   let key = Object.keys(err);
//   for(let i=0; i<key.length; i++){
//     let [category, index, property] = key[i].split('.')
//
//     if(category == 'plan'){
//       switch (property) {
//         case 'plan_title':
//           this.planOutline.mark_title = "＊";
//           break;
//         case 'plan_info':
//           this.planOutline.mark_info = "＊";
//           break;
//         default:
//           console.log(category);
//           console.log(property);
//           console.log('該当なし')
//       }
//     }else if(category == 'spot'){
//       // spotCountを元に対象のdayInfoのインデックス、spotInfoのインデックスを特定
//       let targetDay
//       let targetSpot
//       this.dayInfo.forEach(day => {
//         day.spotInfo.forEach(spot => {
//           if(spot.spotCount == index){
//             targetDay = this.dayInfo.indexOf(day);
//             targetSpot = day.spotInfo.indexOf(spot);
//           }
//         });
//       });
//
//       switch (property) {
//         case 'spot_title':
//           this.dayInfo[targetDay].spotInfo[targetSpot].mark_title = "＊"
//           break;
//         case 'spot_address':
//           this.dayInfo[targetDay].spotInfo[targetSpot].mark_address = "＊"
//           break;
//         case 'spot_info':
//           this.dayInfo[targetDay].spotInfo[targetSpot].mark_info = "＊"
//           break;
//         case 'spot_image':
//           this.dayInfo[targetDay].spotInfo[targetSpot].mark_image = "*"
//         default:
//       }
//     }
//   }
// },
