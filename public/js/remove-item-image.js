document.addEventListener('DOMContentLoaded', ()=>{
    const DELETE_BUTTON = document.querySelector('label[for="wemea_sylius_menu_menu_item_image_remove_image"]');

    const FILE_INPUT = document.getElementById('wemea_sylius_menu_menu_item_image_file');

    const DELETE_CHECKBOX  = document.getElementById('wemea_sylius_menu_menu_item_image_remove_image');

    const IMAGE_PREVIEW_ELEMENT = document.getElementById('wemea_sylius_menu_menu_item_image_preview');

    //Set preview reader
    let fileReader = new FileReader();
    fileReader.onload = function (){
        IMAGE_PREVIEW_ELEMENT.src = fileReader.result;
    }

    DELETE_BUTTON.addEventListener('click', evt => {
        //disabled the default behaviour to force to be checked
        evt.preventDefault();
        DELETE_CHECKBOX.checked = true;
        FILE_INPUT.value = null;
        IMAGE_PREVIEW_ELEMENT.style.display = 'none';
        DELETE_BUTTON.style.display = 'none';
    })

    FILE_INPUT.addEventListener('change', (evt) => {
        DELETE_CHECKBOX.checked = false;

        //preview image
        fileReader.readAsDataURL(evt.target.files[0]);

        IMAGE_PREVIEW_ELEMENT.style.display = 'inline-block';
        DELETE_BUTTON.style.display = 'inline-block';
    })
});
