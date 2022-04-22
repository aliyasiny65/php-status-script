var rawFile = new XMLHttpRequest();
rawFile.open("GET", "language.json", false);
rawFile.onreadystatechange = function ()
{
   var allText = rawFile.responseText;
}
rawFile.send(null);
var jsonfile = rawFile.responseText;
var arrLang = JSON.parse(jsonfile);

$(function() {
$('.translate').click(function() {
var lang = $(this).attr('id');

$('.lang').each(function(index, item) {
$(this).text(arrLang[lang][$(this).attr("key")]);
});
});
});