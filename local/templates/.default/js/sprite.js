import Twig from 'twig';

$(document).ready(function() {
  var files = require.context('../img/sprite/', false, /\.svg$/);
  files.keys().forEach(files);

  let sprOut = $('#sprite-output');
  if (sprOut.length) {
    let sprKeys = files.keys();

    var template = Twig.twig({
      data: '<span class="svg-icon"><svg><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#spr-{{icon.name}}" /></svg></span>'
    });

    let fullHtml = '';
    for (var i = sprKeys.length - 1; i >= 0; i--) {
      let icon = sprKeys[i].slice(2,-4);
      let iconNameTpl = `<p>${icon}</p>`;
      //console.log(icon);
      let html = '<div class="sprite-output__item">' + iconNameTpl + template.render({
        icon: {
          'name': icon
        }
      }) + '</div>';
      fullHtml += html;
    }
    sprOut.html(fullHtml);

  };

});