function saveDesign() {
    e.preventDefault();
    var saveForm = jQuery("#drop_form");
    appendData(saveForm);
    saveForm.submit();
}

function appendData(saveForm) {
    saveForm.append(jQuery("<input>").attr("type", "hidden").attr("name", "title").val(jQuery('#title_front').val()));
    saveForm.append(jQuery("<input>").attr("type", "hidden").attr("name", "description").val(jQuery('#description_front').val()));
    saveForm.append(jQuery("<input>").attr("type", "hidden").attr("name", "main_tag").val(jQuery('#main_tag_front').val()));
    saveForm.append(jQuery("<input>").attr("type", "hidden").attr("name", "tags").val(jQuery('#tags_front').val()));
}