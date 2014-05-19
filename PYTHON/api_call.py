# TME API - example usage with Python
# More info at: https://developers.tme.eu

import collections, urllib, base64, hmac, hashlib, urllib2, json

def product_import_tme():
    # /product/product_import_tme/

    token = '<TOKEN>'
    app_secret = '<APP SECRET>'

    params = {
	'SymbolList[0]' : '1N4002',
        'SymbolList[1]' : '1N4007',
        'Country': 'PL',
        'Currency': 'PLN',
        'Language': 'PL',
    }

    response = api_call('Products/GetPrices', params, token, app_secret, True);
    response = json.loads(response)
    print response

def api_call(action, params, token, app_secret, show_header=False):
    api_url = 'https://api.tme.eu/' + action + '.json';
    params['Token'] = token;

    params = collections.OrderedDict(sorted(params.items()))

    encoded_params = urllib.urlencode(params, '')
    signature_base = 'POST' + '&' + urllib.quote(api_url, '') + '&' + urllib.quote(encoded_params, '')

    api_signature = base64.encodestring(hmac.new(app_secret, signature_base, hashlib.sha1).digest()).rstrip()
    params['ApiSignature'] = api_signature

    opts = {'http' :
               {
               'method' : 'POST',
               'header' : 'Content-type: application/x-www-form-urlencoded',
               'content' : urllib.urlencode(params)
               }
            }

    http_header = {
        "Content-type": "application/x-www-form-urlencoded",
        }
    # create your HTTP request
    req = urllib2.Request(api_url, urllib.urlencode(params), http_header)
    # submit your request
    res = urllib2.urlopen(req)
    html = res.read()
    return html

print product_import_tme();
