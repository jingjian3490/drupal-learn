function pfAnalyticsAttrData(linkName, linkType = 'internal') {
  const dataLayer = [];
  dataLayer.push({
    event: 'pfLinkName',
    pfLinkName: {
      linkName,
      linkType,
    },
  });

  const event = new CustomEvent('pfAnalytics', {
    detail: dataLayer,
  });
  document.querySelector('body').dispatchEvent(event);
}

function pfPageData(pageName, pageGcma = '') {
  window.pfAnalyticsData = {
    pfPage: {
      pageName,
      pageType: 'Generic',
      referringURL: '',
      language: 'en',
      primaryMessageCategory: 'NA',
      pageSource: 'Pfizer',
      contentOrigin: 'NA',
      brand: 'NURTEC',
      'GCMA-ID': 'NA',
      trackingCode: '',
      platform: 'Drupal 10',
      sessionID: 'NA',
      'PAGE-GCMA-ID': pageGcma,
      indication: 'migraine',
      therapeuticArea: 'migraine',
      businessUnit: 'INTERNAL MEDICINE',
      pfizerRegion: 'Singapore',
      region: 'APAC',
      audienceType: '',
      audienceSpecialty: '',
      contentType: 'text/html; charset=UTF-8',
    },
  };
}

export { pfAnalyticsAttrData, pfPageData };
