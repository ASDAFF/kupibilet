function engineAjaxLog(time,type,text_align,text){
    // Создаем обект
    var template_ajax_log = $('#engAjaxLog');
    if(!template_ajax_log.length){
        $('body').prepend('<div id="engAjaxLog"></div>');
        var template_ajax_log = $('#engAjaxLog');
        template_ajax_log.css('position','fixed').css('z-index','1000').css('bottom','0').css('right','0');
    }

    var template_ajax_log_block = $('<div class="engAjaxLog-block"></div>');
    template_ajax_log_block.css('text-align',text_align);
    template_ajax_log_block.html(text); // Добавлям текст
    template_ajax_log.append(template_ajax_log_block); // Добавлям блок в конец
    template_ajax_log_block.effect( "bounce",'',1000); // Показываем
    setTimeout(function(){ // устанавливаем таймер на необходимое время
        template_ajax_log_block.fadeOut(300,function(){ // затем скрываем стикер
            $(this).remove(); // по окончании анимации удаляем его
        });
    },time*1000);
}

$(function() {
    // Поиска по сайту
    $("#search").keyup(function(event){
        var search_pole = $("#search");
        var search_list = $("#elmHeader-search-list");
        var search_list_line = $("#elmHeader-search-lis-line");
        var keyint = 1000; // интервал между нажатиями клавиш

        // Меняем размер

        if(search_pole.val().length >= 1){

            // Время нажатия
            var d1 = new Date();
            time_keyup = d1.getTime();
            console.log(time_keyup);

            search_list.css('display','block');
            search_list_line.css('display','block');
            if(event.keyCode != 40 && event.keyCode != 38 && event.keyCode != 13) {
                search_list.html('<div style="text-align:center;padding:20px;">Загрузка</div>');
                engLoad($(search_list.children("div")),'black','Загрузка...');
                    setTimeout(function(){
                        // Время текущие
                        var d2 = new Date();
                        time_search = d2.getTime();
                        if (time_search-time_keyup>=keyint){

                        $.post("/.modules/teacher/.ajax/.search.php",
                            {TEXT : search_pole.val()},
                            onAjaxSuccess);
                        function onAjaxSuccess(data){search_list.html(data);}
                    }}, keyint);

            }
            $("body").click(function(){
                search_list.html('');
                search_list.css('display','none');
                search_list_line.css('display','none');
            });
        }else {search_list.html('');search_list.css('display','none');search_list_line.css('display','none');}

    });

    engBlockFix();
});
// Изменения размера экрана
$( window ).resize(function() {engBlockFix();});


function engBlockFix() {
    var rightBlockDetail = $('#elmRight-detail');
    var rightBlock = $('.engBox-right');
    var centerBlock = $(".engBox-center");

    if($(document).width() < 1000) {
        rightBlockDetail.detach();
        centerBlock.prepend(rightBlockDetail);
    }
    if($(document).width() > 1000) {
        rightBlockDetail.detach();
        rightBlock.prepend(rightBlockDetail);
    }
}


function engLoad(pole,color,text) {
    var width = pole.innerWidth(); pole.width(width-20);
    var height = pole.innerHeight(); pole.height(height-10);

    if (color == 'black') var stile = 'black ';
    if (color == 'white') var stile = 'white ';
    if (text != '') var stile = stile+'text ';
    if (text == 'default') var text = 'Загрузка';

    var data = '<div class="engLoad '+stile+'">'+text+'</div>';
    pole.html(data);
}
function engFormCheckEmpty(pole,text) {
    if (pole.val().length <= 0) {
        pole.addClass("engForm-error");
        if(text != null && !pole.parent().children(".engColor-error").length) {
            pole.parent().append("<div class='engColor-error'>"+text+"</div>");}
    } else {
        pole.removeClass("engForm-error");
        pole.parent().children(".engColor-error").remove();
    }
}




function modRegionFormList(value) {
    var object = $("[eng-data = modRegionFormList]"); // Обект
    var region_id =  object.find($("[eng-data = region_id]")).val(); // регион
    var city_id =  object.find($("[eng-data = city_id]")).val(); // город
    var institute_id =  object.find($("[eng-data = institute_id]")).val(); // инстит

    if(value == '1'){city_id = null; institute_id = null};
    if(value == '2'){institute_id = null}

    $.post("/.modules/region/.ajax/formList.php",
        {REGION_ID : region_id, CITY_ID: city_id, INSTITUTE_ID: institute_id}, onAjaxSuccess);
    function onAjaxSuccess(data){object.replaceWith(data);}
}

function plugVoice_add(table,table_id,type){
    var content = $("[eng-data = plugVoice][plugVoice-table = "+table+"][plugVoice-table_id = "+table_id+"]"); // Вывод
    $.post("/.plugins/voice/.ajax/add.php",
        {TABLE : table, TABLE_ID: table_id, TYPE: type}, onAjaxSuccess);
    function onAjaxSuccess(data){content.html(data);}
}
function plugCommentAdd(modTeacher_id,modInstitute_id){
    var object = $("[eng-data = plugCommentAdd]"); // Обект
    var text =  object.find($("[eng-data = text]")).val(); // Текст
    var content = $("[eng-data = plugCommentList]"); // Вывод
    $.post("/.plugins/comment/.ajax/add.php",
        {TEACHER_ID : modTeacher_id, INSTITUTE_ID: modInstitute_id, TEXT: text }, onAjaxSuccess);
    function onAjaxSuccess(data){content.html(data);}
}
function plugComment_delete(id){
    var object = $("[eng-data = plugComment-item][eng-id = "+id+"]"); // Обект

    $.post("/.plugins/comment/.ajax/delete.php",
        {ID : id}, onAjaxSuccess);
    function onAjaxSuccess(data){object.fadeOut(300);}
}

function modPost_add() {
    var object = $("[eng-data = modPost_add]"); // Обект
    var title = object.find($("[eng-data = title]")).val(); // Заголовок
    var text = object.find($("[eng-data = text]")).val(); // Текст
    var flag = object.find($("[eng-data = flag]:radio:checked")).val(); // Флаг
    var img_1 = object.find($("[eng-data = img_1]")).files; // Изображение 1
    var img_2 = object.find($("[eng-data = img_2]")).val(); // Изображение 1
    var img_3 = object.find($("[eng-data = img_3]")).val(); // Изображение 1
    var img_4 = object.find($("[eng-data = img_4]")).val(); // Изображение 1
    var img_5 = object.find($("[eng-data = img_5]")).val(); // Изображение 1
    var tag = object.find($("[eng-data = tag]")).val(); // Тэги


}

function modTeacherComment_add(teacher_id){
    var object = $("[eng-data = modTeacherComment_add]"); // Обект
    var type =  object.find($("[eng-data = type]:radio:checked")).val(); // Тип
    var text =  object.find($("[eng-data = text]")).val(); // Текст

    var content = $("[eng-data = modTeacherComment_list]"); // Вывод
    $.post("/.modules/teacher/.ajax/modTeacherComment_add.php",
        {TEACHER_ID : teacher_id, TYPE: type, TEXT: text }, onAjaxSuccess);
    function onAjaxSuccess(data){content.html(data);}
}
function modTeacherAssess_add(teacher_id,value_id,value_number){
    var object = $("[eng-data = modTeacherAssess][eng-teacher_id = "+teacher_id+"]"); // Обект

    var content = $("[eng-data = number][eng-value_id = "+value_id+"]"); // Вывод

    $.post("/.modules/teacher/.ajax/modTeacherAssess_add.php",
        {TEACHER_ID : teacher_id, VALUE_ID: value_id, VALUE_NUMBER: value_number }, onAjaxSuccess);
    function onAjaxSuccess(data){
        content.fadeOut(100);
        content.html(data).fadeIn(300);
        object.find($(".btn a span[eng-value_id = "+value_id+"]")).removeClass("active").addClass("opacity");
        object.find($("[eng-value_id = "+value_id+"][eng-value_number = "+value_number+"]")).removeClass("opacity").addClass("active")
    }
}

function modTeacher_add(STATUS){
    var object = $("[eng-data = modTeacher_add]"); // Обект
    var region_id =  object.find($("[eng-data = region_id]")).val(); // регион
    var city_id =  object.find($("[eng-data = city_id]")).val(); // город
    var institute_id =  object.find($("[eng-data = institute_id]")).val(); // инстит
    var name =  object.find($("[eng-data = name]")).val(); // Имя
    var source =  object.find($("[eng-data = source]")).val(); // Источник

    var content = object; // Вывод
    var load = object.find($("[eng-data = load]")); // Загрузка


    engLoad(load,'black','default');
    $.post("/.modules/teacher/.ajax/add.php",
        {STATUS: STATUS, REGION_ID : region_id, CITY_ID: city_id, INSTITUTE_ID: institute_id , NAME: name,SOURCE: source}, onAjaxSuccess);
    function onAjaxSuccess(data){
        content.replaceWith(data);
     }
}

function engPlay(CODE){
    var player = $("#el-video-player");
    console.log(CODE);
    player.html("<iframe src='https://my.mail.ru/video/embed/"+CODE+"' width='100%' height='375' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>");
    setTimeout(function () {
        var iframe = document.querySelector('iframe');
        iframe = $('#el-video-player').html();
        console.log(iframe);
        iframe = iframe.toString();
        console.log(iframe);
        //player.html(video);
    }, 3000);


}



function OnPlay(){Uppod.trace('OnPlay');
if(!iplay){if(nativecontrols&&!media.controls){
    CSS(controls,{'visibility':'hidden'});
    media.controls=true;Remove('layer');
    media_mc.onclick=null}
    if(poster_mc&&vars.m=='video'){
    poster_mc.style.display='none'}if(play_b!=undefined){play_b.c.style.display='none';pause_b.c.style.display='block'}iplay=true;var hide=vars.cntrlhide==1&&vars.cntrlout==0;var fullHide=ifull&&vars.fullcntrlhide==1;if(hide||fullHide){clearInterval(hideInterval);hideInterval=setInterval(CntrlHide,3000)}if(vars.comment!=undefined&&vars.comment!=''&&vars.showname==1){vars.shownameliketip==1?(vars.shownameonstop==1?Hide(nametip):''):Hide(alrt)}if(vars.plplace=="inside"&&playlist){Hide(playlist)}if(start_b){start_b.c.style.display='none'}if(logo){if(vars.logoplay==1){Show(logo)}else{Hide(logo)}}Event('play');if(!istartevnt){Event('start');istart=true;istartevnt=true}if(vars.sub&&(vars.substart==1||(mobile&&nativecontrols))){CreateSubs()}}}
