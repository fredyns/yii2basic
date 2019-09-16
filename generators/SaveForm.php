<?php

namespace app\generators;

/**
 * Description of SaveForm
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class SaveForm extends \schmunk42\giiant\helpers\SaveForm
{

    /**
     * creata js statement for seting to variable savedFormas array with all forms and it data in json format.
     *
     * @return string
     */
    public static function getSavedFormsJs($generatorName)
    {
        $js = [];

        foreach (self::loadSavedForms($generatorName) as $k => $row) {
            $js[] = "\"".$k."\" : ".$row['jsonData'];
        }

        return 'var savedForms = {'.implode(',', $js).'};';
    }

    public static function jsFillForm()
    {
        return '
    function fillForm(id){
        if (id=="0") return;

        var formData = savedForms[id];
        
        for (var filedName in formData) {
        
            var checkboxName = "[name=\'Generator["+formData[filedName]["name"]+"][]\']";
            if(jQuery(checkboxName).is(":checkbox")){
                $(checkboxName).each(function( index ) {
                    $(this).prop("checked", false);
                    var actualValue = new String($( this ).val());
                    actualValue = actualValue + "";
                    for (var i = 0; i < formData[filedName]["value"].length; i++) {
                        var formValue = new String(formData[filedName]["value"][i]);
                        if(actualValue == formValue){
                            $(this).prop("checked", true);
                            continue;
                        }
                    }
                });                
                continue;
            }
            
            var checkboxName = "[name=\'Generator["+formData[filedName]["name"]+"]\']";
            if(jQuery(checkboxName).is(":checkbox")){
                jQuery(checkboxName).prop("checked", false);
                
                $(checkboxName).each(function( index ) {
                    $(checkboxName).prop("checked", false);
                    if(formData[filedName]["value"] == 1){
                        $(checkboxName).prop("checked", true);
                    }
                });                
                continue;
            }
            
            var fieldId = "generator-" + filedName;
            if (jQuery("#" + fieldId).is("input") || jQuery("#" + fieldId).is("select")){
                jQuery("#" + fieldId).val(formData[filedName]["value"]).trigger("input");
                continue;
            }    
        }    
    }
        ';
    }

}