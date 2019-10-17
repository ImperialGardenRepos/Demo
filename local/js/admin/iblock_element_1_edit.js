var obIblockElementEdit1 = {
    init: function () {
        $("#PROP_GROUP").change(function () {
            let $obGroupSelect = $(this);
            obIblockElementEdit1.processGroupChange($obGroupSelect.val());
        });
        $("#PROP_GROUP").change();
        
        $(".js-IS_VIEW:checkbox").change(function() {
            obIblockElementEdit1.processElementTypeChange($(this).is(":checked"));
        });
        $(".js-IS_VIEW:checkbox").change();
        
        $("#save, #apply, #save_and_add").click(function() {
            $(".js-tr-group-addictable.hidden").remove();
        });
    },
    
    setSortDefaultName: function() {
        let intSectionID = $("#edit1_edit_table input[name=IBLOCK_SECTION\\[\\]]").val();
    
        $.ajax({
            method: "GET",
            url: "/local/ajax/system.php",
            dataType: "json",
            data: { action: "getSortDefaultName", intSectionID: intSectionID }
        })
            .done(function( obResult ) {
                if(obResult.RESULT == "OK") {
                    $("#NAME").val(obResult.NAME);
                }
            });
        
        return false;
    },
    
    processElementTypeChange: function(bIsView) {
        if(bIsView)
            $("#edit1_edit_table").addClass("js-is-view");
        else $("#edit1_edit_table").removeClass("js-is-view");
    },
    
    processGroupChange: function(strValue) {
        if(strValue.length > 0) {
            let intGroupID = arGroups[strValue]["ID"];
    
            // показываем строки таблицы только с нужными свойствами
            $(".js-tr-group-addictable").each(function() {
                let $obT = $(this),
                    strPropertyCode = $obT.data("code"),
                    arLinks = arPropertyGroups[strPropertyCode]["LINKS"];
               
                if(obHelper.inObject(intGroupID, arLinks)) {
                    $obT.removeClass("hidden");
                } else {
                    $obT.addClass("hidden");
                }
            });
    
            $(".js-group-addictable").each(function() {
                let $obT = $(this),
                    strPropertyCode = $obT.data("code");

                if($obT.prop('nodeName') == 'SELECT' && !$obT.hasClass('js-uni-values')) {
                    let arPropertyData = arPropertyTree[strPropertyCode];
                    
                    $obT.find('option').addClass("hidden");
                    $obT.find('option[value=""]').removeClass("hidden");
                    for(let i in arPropertyData["VALUES"]) {
                        let strXmlID = arPropertyData["VALUES"][i]["XML_ID"],
                            arLinks = arPropertyData["VALUES"][i]["LINK"];

                        if(obHelper.inObject(intGroupID, arLinks)) {
                            $obT.find('option[value="'+strXmlID+'"]').removeClass("hidden");
                        }
                    }
                    
                    
                    let bSwitchOption = false,
                        obSelectedOption = $obT.find("option:selected");
                    
                    if(obSelectedOption.size() > 0) {
                        if(obSelectedOption.css("display") == 'none') {
                            bSwitchOption = true;
                        }
                    } else {
                        bSwitchOption = true;
                    }
                    
                    if(bSwitchOption) {
                        $obT.find('option[value=""]').prop("selected", true);
                    }
                }
            });
        } else {
            $(".js-tr-group-addictable").addClass("hidden");
        }
    },
    
    disableAreas: function () {
        $("#edit1").append('<div id="disabled-area-1"></div>');
        $("#edit1 input").on('focus', function () {
            $("#tab_cont_cedit3").click();
        });
        
        let intWidth = $("#edit1").width(),
            intHeight = $("#edit1").height();
        
        if (intWidth < 907) intWidth = 907;
        
        $("#disabled-area-1").height(intHeight).width(intWidth).click(function (event) {
            event.preventDefault();
            event.stopPropagation();
            
            $("#tab_cont_cedit3").click();
        });
    }
};

$(function() {
    obIblockElementEdit1.init();
});