
$('body').on('click', (e) => {

  console.log(e.target.id);

  if(e.target.id == 'modal-outer' || e.target.id == 'modal-box'){
    let div_element = $('#modal-content');

    div_element.attr({
      style: 'display: none',
    });

  }

})
