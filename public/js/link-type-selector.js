document.addEventListener('DOMContentLoaded', ()=>{
console.log("debug #1");
    const SELECT_TYPE_ELEMENT = document.getElementById('wemea_sylius_menu_menu_item_link_type');
    const PROPERTIES_LINKS_INPUTS = document.querySelectorAll('#wemea_sylius_menu_link_selector [data-link-type]');

    function displayInputAccordingTheSelector(){
        let currentValue = SELECT_TYPE_ELEMENT.value;
  console.log("debug #2", currentValue);

        [...PROPERTIES_LINKS_INPUTS].forEach(el => {
  console.log(el.dataset.linkType, el);
            if (el.dataset.linkType === currentValue){
                el.style.display = 'block';
            } else {
                el.style.display = 'none';
            }
        })
    }

    SELECT_TYPE_ELEMENT.addEventListener('change', displayInputAccordingTheSelector);

    //call one time by default to show default input
    displayInputAccordingTheSelector();
});
