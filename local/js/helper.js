var obHelper = {
    arTranslitRu: ['Я','я','Ю','ю','Ч','ч','Ш','ш','Щ','щ','Ж','ж','А','а','Б','б','В','в','Г','г','Д','д','Е','е','Ё','ё','З','з','И','и','Й','й','К','к','Л','л','М','м','Н','н', 'О','о','П','п','Р','р','С','с','Т','т','У','у','Ф','ф','Х','х','Ц','ц','Ы','ы','Ь','ь','Ъ','ъ','Э','э'],
    
    arTranslitEn: ['Ya','ya','Yu','yu','Ch','ch','Sh','sh','Sh','sh','Zh','zh','A','a','B','b','V','v','G','g','D','d','E','e','E','e','Z','z','I','i','J','j','K','k','L','l','M','m','N','n', 'O','o','P','p','R','r','S','s','T','t','U','u','F','f','H','h','C','c','Y','y','`','`','\'','\'','E', 'e'],

    transliterateText: function(strText, strDirection) {
        strDirection = strDirection=='en2ru' ? 'en2ru' : 'ru2en';
    
        if(strDirection == 'ru2en') {
            for(var i=0; i<obHelper.arTranslitRu.length; i++) {
                var reg = new RegExp(obHelper.arTranslitRu[i], "g");
                strText = strText.replace(reg, obHelper.arTranslitEn[i]);
            }
        } else if(strDirection == 'en2ru') {
            for(var i=0; i<obHelper.arTranslitEn.length; i++) {
                var reg = new RegExp(obHelper.arTranslitEn[i], "g");
                strText = strText.replace(reg, obHelper.arTranslitRu[i]);
            }
        }
        
        return strText;
    },

    transliterate: function(strFrom, strTo, strDirection)
    {
        strFrom = strFrom || 'NAME';
        strTo = strTo || 'CODE';
    
        let strText = $("#"+strFrom).val();
        
        $.ajax({
            method: "POST",
            url: "/local/ajax/system.php",
            dataType: "json",
            data: { action: "translit", strText: strText, strDirection: strDirection}
        })
            .done(function( obResult ) {
                if(obResult.RESULT == 'OK') {
                    $("#"+strTo).val(obResult.TEXT);
                }
            });
        
        return false;
    },
    
    inObject: function(value, object) {
        for(var i=0 in object) {
            if(value == object[i]) return true;
        }
        return false;
    },
    
    processPagerShowMore: function ($obT) {
        let strUrl = $obT.data("url") || '',
            strContainerID = $obT.data("continer-id") || '';
        
        if (strUrl.length > 0) {
            $.ajax({
                method: "GET",
                url: strUrl
            })
                .done(function (strResult) {
                    let obLoadedPager = $(strResult).find("#show-more-"+strContainerID);
                    let obLoadedItems = $(strResult).find("#container-"+strContainerID).html();
                    
                    $("#container-"+strContainerID).append(obLoadedItems);
                    $("#show-more-"+strContainerID).replaceWith(obLoadedPager);
    
                    bind_widgets("#container-"+strContainerID);
                });
        }
    },
    
    processReloadContainer: function() {
        $(".js-reload-container").each(function() {
            let $obT = $(this),
                strContainerID = $obT.data("container");
            
            if(!$obT.hasClass("js-reload-container--inited")) {
                $obT.on('click', function() {
                    $.ajax({
                        method: "GET",
                        url: $obT.attr("href")
                    })
                        .done(function( strResult ) {
                            $("#"+strContainerID).replaceWith($(strResult).find("#"+strContainerID));
                            obHelper.processReloadContainer();
                            bind_widgets("#"+strContainerID);
                        });
        
                    return false;
                }).addClass('js-reload-container--inited');
            }
        });
    },
    
    initPage: function() {
        obHelper.processReloadContainer();
    },
};