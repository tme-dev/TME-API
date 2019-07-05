# TME API - example usage with Python
# More info and create application at: https://developers.tme.eu

import collections, urllib, base64, hmac, hashlib, urllib3, json

# required for PyCharm :
from urllib import parse, request

def product_import_tme():
    # /product/product_import_tme/

    token = '<TOKEN>'
    app_secret = '<APP SECRET>'

    params = {
        'SymbolList[0]' : 'NE555D',
        'SymbolList[1]' : '1N4007-DC',
        'Country': 'PL',
        'Currency': 'PLN',
        'Language': 'PL',
    }

    response = api_call('Products/GetParameters', params, token, app_secret, True)
    response = json.loads(response)
    return response

def api_call(action, params, token, app_secret, show_header=False):
    api_url = 'https://api.tme.eu/' + action + '.json'
    params['Token'] = token

    params = collections.OrderedDict(sorted(params.items()))

    encoded_params = urllib.parse.urlencode(params, '')
    signature_base = 'POST' + '&' + urllib.request.quote(api_url, '') + '&' + urllib.request.quote(encoded_params, '')
    app_secret= bytes(app_secret , 'latin-1')
    signature_base = bytes(signature_base, 'latin-1')
    api_signature = base64.encodestring(hmac.new(app_secret, signature_base, hashlib.sha1).digest()).rstrip()
    params['ApiSignature'] = api_signature

    opts = {
        'http': {
            'method' : 'POST',
            'header' : 'Content-type: application/x-www-form-urlencoded',
            'content' : urllib.parse.urlencode(params)
        }
    }

    http_header = {
        "Content-type": "application/x-www-form-urlencoded",
    }

    # create your HTTP request
    data = urllib.parse.urlencode(params)
    data = data.encode('latin-1')
    req = urllib.request.Request(api_url, data, http_header)

    # submit your request
    res = urllib.request.urlopen(req)
    html = res.read()

    return html

print(product_import_tme())
