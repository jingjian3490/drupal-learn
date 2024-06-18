import { pfPageData } from '../utils';

(($, drupalSettings) => {
  pfPageData('home');
  const $body = $('body');
  const $supportLink = $('#block-pfrpsg-migraine-support a');
  const $bottomReferenceLink = $('#block-pfrpsg-reference a');
  const $allLink = $('.layout-container a');

  $supportLink.each(function () {
    const text = $(this).text().toLowerCase();
    $(this).attr('sc:linkname', `home | content | ${text}`);
  });
  $bottomReferenceLink.attr('sc:linkname', 'home| content | reference');
  $body.on('click', '.external-link-popup-content', function (e) {
    const outerWidth = $(this).outerWidth();
    const offsetLeft = outerWidth - 25 - 39;
    const offsetRight = outerWidth - 25;
    const xIn = e.offsetX >= offsetLeft && e.offsetX <= offsetRight;
    const yIn = e.offsetY >= 22 && e.offsetY <= 59;
    if (xIn && yIn) {
      $('.cancel').trigger('click');
    }
  });

  // whitelist
  const settings = drupalSettings.external_link_popup;
  // RegExp /\s*,\s*|\s+/ supports both space and comma delimiters
  // and its combination.
  const whitelist = settings.whitelist
    ? settings.whitelist.split(/\s*,\s*|\s+/)
    : [];
  const current = window.location.host || window.location.hostname;
  whitelist.unshift(current);
  if (current.substr(0, 4) === 'www.') {
    whitelist.push(current.substr(4));
  } else {
    whitelist.push(`www.${current}`);
  }

  function externalPopup($ele) {
    const href = $ele.attr('href');
    const modalEle = document.createElement('div');
    const $modalEle = $(modalEle);
    $modalEle.addClass(
      'external-link-popup-id-default external-link-popup ui-dialog',
    );

    const modalBody = document.createElement('div');
    const $modalBody = $(modalBody);
    $modalBody.addClass('external-link-popup-body');
    $modalBody.append(
      '<p>You are now leaving a Pfizer-operated website. Links to all outside sites are provided as a resource to our visitors. Pfizer accepts no responsibility for the content of sites that are not owned and operated by Pfizer.</p>',
    );

    const modalContent = document.createElement('div');
    const $modalContent = $(modalContent);
    $modalContent.addClass('external-link-popup-content');
    $modalContent.append(modalBody);

    const modalButtonPane = document.createElement('div');
    const $modalButtonPane = $(modalButtonPane);
    $modalButtonPane.addClass('ui-dialog-buttonpane ui-helper-clearfix');
    $modalButtonPane.append(
      '<div class="ui-dialog-buttonset"><button type="button" class="ui-button ui-widget button go-to-link">Go to link</button><button type="button" class="ui-button ui-widget button cancel">Cancel</button></div>',
    );
    $modalEle.append(modalContent);
    $modalEle.append(modalButtonPane);

    const overlay = document.createElement('div');
    $(overlay).addClass('ui-widget-overlay');
    $(overlay).css('z-index', '1259');
    $body.append(modalEle);
    $body.append(overlay);
    $('.go-to-link').on('click', function () {
      const a = document.createElement('a');
      a.href = href;
      a.target = '_blank';
      $(a).attr(
        'sc:linkname',
        'global | content | external popup | Go to link',
      );
      document.body.appendChild(a);
      a.click();
      a.remove();
      modalEle.remove();
      overlay.remove();
    });
    $('.cancel').on('click', function () {
      modalEle.remove();
      overlay.remove();
    });
  }

  function pregEscape(str) {
    // eslint-disable-next-line
    return (str + '').replace(
      // eslint-disable-next-line
      new RegExp('[.\\\\+*?\\[\\^\\]$(){}=!<>|:\\-]', 'g'),
      '\\$&',
    );
  }
  function inDomain(domain, domains) {
    if (typeof domain !== 'string') {
      return false;
    }
    // eslint-disable-next-line
    for (let i in domains) {
      if (
        domain.toLowerCase() === domains[i].toLowerCase() ||
        // eslint-disable-next-line
        domain.match(new RegExp('\\.' + pregEscape(domains[i]) + '$', 'i'))
      ) {
        return true;
      }
    }
    return false;
  }
  function getDomain(url) {
    if (typeof url !== 'string') {
      return null;
    }
    const matches = url.match(/\/\/([^/]+)\/?/);
    return matches && matches[1];
  }

  $allLink.each(function () {
    const $this = $(this);
    const domain = getDomain($this.attr('href'));
    if (!domain) {
      // It's internal link, return without events.
      return;
    }
    if (!inDomain(domain, whitelist)) {
      $this.on('click', function (e) {
        e.preventDefault();
        externalPopup($this);
      });
    }
  });
})(jQuery, drupalSettings);
