let obIblockElementEdit2 = {
    lastProductID: 0,
    strContainerID: '',
    arValueMap: {
        "js-tr-MULTISTEMMED": [1, 4, 9, 11],
        "js-tr-STEM_GIRTH": [1, 4],
        "js-tr-CROWN_WIDTH": [1, 4, 11, 12],
        "js-tr-HEIGHT1": [1, 4, 10, 11, 12],
        "js-tr-HEIGHT2": [1, 4, 5,10, 11, 12],
        "js-tr-AGES": [8],
        "js-tr-HEIGHT_NOW_EXT": [1, 3, 4, 5, 8, 9, 10, 11, 12],
        // "js-tr-CONTAINER_SIZE": [2]
    },
    
    arContainerMap: {
        59: [7],
        62: [1, 4, 5, 8, 9, 11, 12],
        61: [2, 3, 5, 6, 7, 11, 12],
        60: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
    },
    
    checkSelectionChange: function() {
        let newVal = $("#"+obIblockElementEdit2.strContainerID).find("input").val();
        
        if(typeof(newVal) != 'undefined') {
            if (newVal.length > 0) {
                if (newVal != obIblockElementEdit2.lastProductID) {
                    obIblockElementEdit2.processParentChange(newVal);
                    obIblockElementEdit2.lastProductID = newVal;
                }
            } else {
                obIblockElementEdit2.lastProductID = 0;
                $(".js-tr-group-addictable:visible").hide();
            }
        } else {
            obIblockElementEdit2.lastProductID = 0;
            $(".js-tr-group-addictable:visible").hide();
        }
    },
    
    init: function () {
        let strControlID = ($(".js-CML2_LINK").attr("id"))
            .split("visual_")
            .join("value_container_");
    
        obIblockElementEdit2.strContainerID = strControlID;
        setInterval("obIblockElementEdit2.checkSelectionChange();", 250);
    
        $("#PROP_PACK").change(function() {
            obIblockElementEdit2.processPackChange();
        });
        $("#PROP_PACK").change();
        
        $(".js-tr-group-addictable").hide();
        
        $("#save, #apply, #save_and_add").click(function() {
            $("tr[class|='js-tr']:hidden").remove();
        });
    
        obIblockElementEdit2.processParentChange(false);
    },

    setOfferDefaultName: function(intParentID) {
        intParentID = intParentID || $("#"+obIblockElementEdit2.strContainerID).find("input").val();
        
        $.ajax({
            method: "GET",
            url: "/local/ajax/system.php",
            dataType: "json",
            data: { action: "getOfferName", intID: intParentID }
        })
            .done(function( obResult ) {
                if(obResult.RESULT == "OK") {
                    $("#NAME").val(obResult.NAME);
                }
            });
        
        return false;
    },
    
    processPackChange: function() {
        let intPackValue = $("#PROP_PACK").val() || 0;
        
        if(intPackValue == 60) {
            $(".js-tr-CONTAINER").show();
        } else {
            $(".js-tr-CONTAINER").hide();
        }
    },
    
    processParentChange: function(intParentID) {
        if(intParentID.length>0) {
            intParentID = parseInt(intParentID);
    
            let strName = $("#NAME").val();
            if(strName.length == 0) {
                obIblockElementEdit2.setOfferDefaultName(intParentID);
            }
            
            $.ajax({
                method: "GET",
                url: "/local/ajax/system.php",
                dataType: "json",
                data: { action: "getProductGroup", intID: intParentID }
            })
                .done(function( obResult ) {
                    if(obResult.RESULT == "OK") {
                        for(let strCode in obIblockElementEdit2.arValueMap) {
                            if(obHelper.inObject(obResult.GROUP, obIblockElementEdit2.arValueMap[strCode])) {
                                $("."+strCode).show();
                            } else $("."+strCode).hide();
                        }
                        
                        let $firstVisibleOption = false;
                        $("#PROP_PACK option").each(function() {
                            let $obOption = $(this),
                                intOptionValue = $obOption.attr("value");
                            
                            if(
                                intOptionValue.length==0
                                    ||
                                obHelper.inObject(obResult.GROUP, obIblockElementEdit2.arContainerMap[intOptionValue])
                            ) {
                                $obOption.show();
                                if(!$firstVisibleOption) {
                                    $firstVisibleOption = $obOption;
                                }
                            } else $obOption.hide();
                        });
    
                        let bSwitchOption = false,
                            obSelectedOption = $("#PROP_PACK").find("option:selected");
    
                        if(obSelectedOption.size() > 0) {
                            if(obSelectedOption.css("display") == 'none') {
                                bSwitchOption = true;
                            }
                        } else {
                            bSwitchOption = true;
                        }
    
                        if(bSwitchOption) {
                            $firstVisibleOption.prop('selected', true);
                        }
                        
                        $("#PROP_PACK").change();
                    } else {
                        for(let strCode in obIblockElementEdit2.arValueMap) {
                            $("." + strCode).hide();
                        }
    
                        ("#PROP_PACK option").each(function() {
                            $(this).hide();
                        });
                    }
                });
        } else {
            for(let strCode in obIblockElementEdit2.arValueMap) {
                $("." + strCode).hide();
            }
        }
    }
};

$(function() {
    setTimeout('obIblockElementEdit2.init();', 500);
});

