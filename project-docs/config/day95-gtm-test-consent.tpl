___INFO___

{
  "type": "TAG",
  "id": "cvt_temp_public_id",
  "version": 1,
  "securityGroups": [],
  "displayName": "DentAll Basic Consent",
  "brand": {
    "id": "brand_dummy",
    "displayName": ""
  },
  "description": "",
  "containerContexts": [
    "WEB"
  ]
}


___TEMPLATE_PARAMETERS___

[]

___SANDBOXED_JS_FOR_WEB_TEMPLATE___

const read = require('copyFromDataLayer');
const defaults = require('setDefaultConsentState');
const update = require('updateConsentState');
const set = require('setInWindow');
const live = require('callInWindow');
const push = require('createQueue')('dataLayer');
const changing = read('event') === 'dentall_consent_update';
const revision = read('dentall_consent_revision');
const allowed = changing && read('dentall_analytics_consent') === 'granted'
  && typeof revision === 'number' && revision > 0
  && live('dentallMeasurementConsent.hasConsent', revision) === true;
// 撤回先停止当前 Google 测量，再更新同意状态。
set('ga-disable-G-B8228WMX3C', !allowed, true);
const state = {analytics_storage: allowed ? 'granted' : 'denied', ad_storage: 'denied', ad_user_data: 'denied', ad_personalization: 'denied'};
if (changing) {
  update(state);
  if (allowed) push({event: 'dentall_analytics_ready', dentall_consent_revision: revision});
} else {
  defaults(state);
}
data.gtmOnSuccess();

___WEB_PERMISSIONS___

[
  {
    "instance": {
      "key": {
        "versionId": "1",
        "publicId": "access_globals"
      },
      "param": [
        {
          "key": "keys",
          "value": {
            "listItem": [
              {
                "mapValue": [
                  {
                    "string": "dataLayer",
                    "type": 1
                  },
                  {
                    "boolean": true,
                    "type": 8
                  },
                  {
                    "boolean": true,
                    "type": 8
                  },
                  {
                    "boolean": false,
                    "type": 8
                  }
                ],
                "mapKey": [
                  {
                    "string": "key",
                    "type": 1
                  },
                  {
                    "string": "read",
                    "type": 1
                  },
                  {
                    "string": "write",
                    "type": 1
                  },
                  {
                    "string": "execute",
                    "type": 1
                  }
                ],
                "type": 3
              },
              {
                "mapValue": [
                  {
                    "string": "ga-disable-G-B8228WMX3C",
                    "type": 1
                  },
                  {
                    "boolean": false,
                    "type": 8
                  },
                  {
                    "boolean": true,
                    "type": 8
                  },
                  {
                    "boolean": false,
                    "type": 8
                  }
                ],
                "mapKey": [
                  {
                    "string": "key",
                    "type": 1
                  },
                  {
                    "string": "read",
                    "type": 1
                  },
                  {
                    "string": "write",
                    "type": 1
                  },
                  {
                    "string": "execute",
                    "type": 1
                  }
                ],
                "type": 3
              },
              {
                "type": 3,
                "mapKey": [
                  {
                    "type": 1,
                    "string": "key"
                  },
                  {
                    "type": 1,
                    "string": "read"
                  },
                  {
                    "type": 1,
                    "string": "write"
                  },
                  {
                    "type": 1,
                    "string": "execute"
                  }
                ],
                "mapValue": [
                  {
                    "type": 1,
                    "string": "dentallMeasurementConsent.hasConsent"
                  },
                  {
                    "type": 8,
                    "boolean": false
                  },
                  {
                    "type": 8,
                    "boolean": false
                  },
                  {
                    "type": 8,
                    "boolean": true
                  }
                ]
              }
            ],
            "type": 2
          }
        }
      ]
    },
    "clientAnnotations": {
      "isEditedByUser": true
    },
    "isRequired": true
  },
  {
    "instance": {
      "key": {
        "versionId": "1",
        "publicId": "read_data_layer"
      },
      "param": [
        {
          "key": "allowedKeys",
          "value": {
            "string": "specific",
            "type": 1
          }
        },
        {
          "key": "keyPatterns",
          "value": {
            "listItem": [
              {
                "string": "event",
                "type": 1
              },
              {
                "string": "dentall_analytics_consent",
                "type": 1
              },
              {
                "type": 1,
                "string": "dentall_consent_revision"
              }
            ],
            "type": 2
          }
        }
      ]
    },
    "clientAnnotations": {
      "isEditedByUser": true
    },
    "isRequired": true
  },
  {
    "instance": {
      "key": {
        "versionId": "1",
        "publicId": "access_consent"
      },
      "param": [
        {
          "key": "consentTypes",
          "value": {
            "listItem": [
              {
                "mapValue": [
                  {
                    "string": "analytics_storage",
                    "type": 1
                  },
                  {
                    "boolean": false,
                    "type": 8
                  },
                  {
                    "boolean": true,
                    "type": 8
                  }
                ],
                "mapKey": [
                  {
                    "string": "consentType",
                    "type": 1
                  },
                  {
                    "string": "read",
                    "type": 1
                  },
                  {
                    "string": "write",
                    "type": 1
                  }
                ],
                "type": 3
              },
              {
                "mapValue": [
                  {
                    "string": "ad_storage",
                    "type": 1
                  },
                  {
                    "boolean": false,
                    "type": 8
                  },
                  {
                    "boolean": true,
                    "type": 8
                  }
                ],
                "mapKey": [
                  {
                    "string": "consentType",
                    "type": 1
                  },
                  {
                    "string": "read",
                    "type": 1
                  },
                  {
                    "string": "write",
                    "type": 1
                  }
                ],
                "type": 3
              },
              {
                "mapValue": [
                  {
                    "string": "ad_user_data",
                    "type": 1
                  },
                  {
                    "boolean": false,
                    "type": 8
                  },
                  {
                    "boolean": true,
                    "type": 8
                  }
                ],
                "mapKey": [
                  {
                    "string": "consentType",
                    "type": 1
                  },
                  {
                    "string": "read",
                    "type": 1
                  },
                  {
                    "string": "write",
                    "type": 1
                  }
                ],
                "type": 3
              },
              {
                "mapValue": [
                  {
                    "string": "ad_personalization",
                    "type": 1
                  },
                  {
                    "boolean": false,
                    "type": 8
                  },
                  {
                    "boolean": true,
                    "type": 8
                  }
                ],
                "mapKey": [
                  {
                    "string": "consentType",
                    "type": 1
                  },
                  {
                    "string": "read",
                    "type": 1
                  },
                  {
                    "string": "write",
                    "type": 1
                  }
                ],
                "type": 3
              }
            ],
            "type": 2
          }
        }
      ]
    },
    "clientAnnotations": {
      "isEditedByUser": true
    },
    "isRequired": true
  }
]

___TESTS___

scenarios: []

___NOTES___

仅供 CR-018 隔离 Local 与 DentAll Test 的未发布 GTM 草稿；不得发布或用于共享环境。
