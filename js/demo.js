var olinktv;
jQuery(document).ready(function(){
    jQuery('#addvideob').click(function(){
        var aname = jQuery("#videoname").val();
        var alinktv = jQuery("#linktv").val();
        if (aname.trim() === '' || alinktv.trim() === '') {
            alert("Fill video name and link!");
        } else {
        jQuery.ajax({
            type: 'POST',
            url: DB_man.ajaxurl,
            data: {
                "action": "post_video_to_db",
                "videoname":aname,
                "linktv":alinktv
            },
            success: function(data){
                document.getElementById("addvideof").reset();
                setInterval('document.location.reload()', 500);
//                alert(data);
            }
        });
    }
    });
    jQuery('#videos').change(function(){
        var urvn = jQuery("#videos").val();
        document.getElementById("ruserwaf").reset();
        if (urvn === "default") {
            document.getElementById("urvideof").reset();
            document.getElementById("removevideob").disabled = true;
            document.getElementById("updatevideob").disabled = true;
        } else {
        const myObj = JSON.parse(urvn);
        document.getElementById("urvideoname").value = myObj["name"];
        document.getElementById("urlinktv").value = myObj["linktv"];
        olinktv=myObj["linktv"];
        document.getElementById("removevideob").disabled = false;
        }
    });
    jQuery('#removevideob').click(function(){
        var urvn = jQuery("#videos").val();
        const myObj = JSON.parse(urvn);
        var videoid = myObj["id"];
//        alert("Video id: " + videoid + ".");
        jQuery.ajax({
            type: 'POST',
            url: DB_man.ajaxurl,
            data: {
                "action": "post_remove_video",
                "id": videoid
            },
            success: function(data){
                document.getElementById("urvideof").reset();
                setInterval('document.location.reload()', 1000);
//                alert(data);
            }
        });
    });
    jQuery('#updatevideob').click(function(){
        var urvn = jQuery("#videos").val();
        const myObj = JSON.parse(urvn);
        var videoid = myObj["id"];
        var videonameu = jQuery("#urvideoname").val();
        var linktvu = jQuery("#urlinktv").val();
        jQuery.ajax({
            type: 'POST',
            url: DB_man.ajaxurl,
            data: {
                "action": "post_update_video",
                "id": videoid,
                "videoname": videonameu,
                "linktv": linktvu
            },
            success: function(data){
                document.getElementById("urvideof").reset();
                setInterval('document.location.reload()', 500);
//                alert(data);
            }
        });
    });
    jQuery("#videos").change(function(){
        jQuery('#userswas').empty();
        var urvn = jQuery("#videos").val();
        if (urvn === "default") {
            document.getElementById("adduserwaf").reset();
            setInterval('document.location.reload()', 500);
        } else {
        const myObj = JSON.parse(urvn);
        var videoid = myObj["id"];
        jQuery.ajax({
            type: 'POST',
            url: DB_man.ajaxurl,
            data: {
                'action': 'get_users_by_ajax',
                'vid': videoid
            },
//            dataType: "json",
            success: function(data){
                jQuery("#userswas").append('<option value="default">--SELECT USER--</option>');
                str = data.substring(0, data.length - 1);
                const myObj = JSON.parse(str);
                var len = myObj.length;
                for( var i = 0; i<len; i++){
                    var name = myObj[i]["user_name"];
                    var id = myObj[i]["id"];
                    jQuery("#userswas").append(new Option(name, id));
                }
            }
        });
    }
    });
    jQuery('#userswas').change(function(){
        document.getElementById("ruserwaf").reset();
        var ruwan = jQuery("#userswas option:selected").text();
        var ruwaid = jQuery("#userswas").val();
        if (ruwaid === "default") {
            document.getElementById("removeuserwab").disabled = true;
            document.getElementById("adduserwab").disabled = true;
            document.getElementById("ruserwaf").reset();
            document.getElementById("adduserwaf").reset();
        } else {
        document.getElementById("ruserwaname").value = ruwan;
        document.getElementById("ruserwaid").value = ruwaid;
        document.getElementById("removeuserwab").disabled = false;
        }
    });
    jQuery('#userwaname').change(function(){        
        var adduwan = jQuery("#userwaname").val();
        if (adduwan === "default") {
            document.getElementById("adduserwab").disabled = true;
        } else {
            document.getElementById("adduserwab").disabled = false;
        }
    });
    jQuery("#videos").change(function(){
        jQuery('#userwaname').empty();
        var urvn = jQuery("#videos").val();
        const myObj = JSON.parse(urvn);
        var videoid = myObj["id"];
        jQuery.ajax({
            type: 'POST',
            url: DB_man.ajaxurl,
            data: {
                'action': 'offer_users_by_ajax',
                'vid': videoid
            },
            success: function(datao){
                jQuery("#userwaname").append('<option value="default">-SELECT WP USER-</option>');
                str = datao.substring(0, datao.length - 1);
//                alert(str);
                const myObjo = JSON.parse(str);
                if (!jQuery.isEmptyObject(myObjo)) {
                    var len = myObjo.length;
//                    alert(len);
                    for( var i = 0; i<len; i++){
                        var name = myObjo[i];
                        jQuery("#userwaname").append(new Option(name));
                    }
                }
            }
        });
    });
    jQuery("#urlinktv").on('keyup blur', function(){
        var ulinktv = jQuery("#urlinktv").val();
        if (ulinktv !== olinktv) {
            document.getElementById("updatevideob").disabled = false;
        } else {
            document.getElementById("updatevideob").disabled = true;
        }
    });
    jQuery('#adduserwab').click(function(){
        var auser = jQuery("#userwaname").val();
        var avid = jQuery("#videos").val();
        const myObj = JSON.parse(avid);
        var avidid = myObj["id"];
        jQuery.ajax({
            type: 'POST',
            url: DB_man.ajaxurl,
            data: {
                "action": "post_adduser_to_db",
                "userwaname":auser,
                "vid":avidid
            },
            success: function(data){
                document.getElementById("adduserwaf").reset();
                document.getElementById("userswaf").reset();
                setInterval('document.location.reload()', 1000);
            }
        });
    });
    jQuery('#removeuserwab').click(function(){
        var ruwaid = jQuery("#ruserwaid").val();
//        alert("User id: " + ruwaid + ".");
        jQuery.ajax({
            type: 'POST',
            url: DB_man.ajaxurl,
            data: {
                "action": "post_remove_userwa",
                "ruserwaid": ruwaid
            },
            success: function(data){
                document.getElementById("ruserwaf").reset();
                setInterval('document.location.reload()', 500);
//                alert(data);
            }
        });
    });
    jQuery("#videoname").on('keyup blur', function(){
        var aname = jQuery("#videoname").val();
        var alinktv = jQuery("#linktv").val();
        if (aname.trim() === '' || alinktv.trim() === '') {
            document.getElementById("addvideob").disabled = true;
        } else {
            document.getElementById("addvideob").disabled = false;
        }
        
    });
    jQuery("#linktv").on('keyup blur', function(){
        var aname = jQuery("#videoname").val();
        var alinktv = jQuery("#linktv").val();
        if (aname.trim() === '' || alinktv.trim() === '') {
            document.getElementById("addvideob").disabled = true;
        } else {
            document.getElementById("addvideob").disabled = false;
        }
        
    });
});
