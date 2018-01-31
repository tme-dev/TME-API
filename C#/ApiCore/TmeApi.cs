using Newtonsoft.Json.Linq;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Net.Http;
using System.Net.Http.Headers;
using System.Security.Cryptography;
using System.Text;
using System.Threading.Tasks;
using TME_API_CSharp_Example.Objects;

namespace TME_API_CSharp_Example.ApiCore
{
    public class TmeApi
    {
        public string ApiCall(string action, Dictionary<string, string> apiParams)
        {
            // All code in this function is based on documentation https://developers.tme.eu/en/

            string uri = $@"https://api.tme.eu/{action}.json";

            // Encode and normalize params
            FormUrlEncodedContent urlEncodedContent = new FormUrlEncodedContent(apiParams);
            string encodedParams = urlEncodedContent.ReadAsStringAsync().Result;

            // Calculate signature basis according the documentation
            string escapedUri = UrlEncode(uri);
            string escapedParams = UrlEncode(encodedParams);
            string signatureBase = $"POST&{escapedUri}&{escapedParams}";

            // Calculate HMAC-SHA1 from signature and encode by Base64 function
            byte[] hmacSha1 = HashHmac(signatureBase, ApiCredentials.AppSecret);
            string apiSignature = Convert.ToBase64String(hmacSha1);

            // Add ApiSignature to params
            apiParams.Add("ApiSignature", apiSignature);
            // Send POST message and return .json content as result
            return SendMessage(uri, new FormUrlEncodedContent(apiParams));
        }

        public bool IsStatusOK(string jsonContent, out ErrorJResult errorResult)
        {
            JObject json = JObject.Parse(jsonContent);
            string status = json["Status"].ToString();
            if (status == "OK")
            {
                errorResult = null;
                return true;
            }
            else
            {
                errorResult = new ErrorJResult(status, json["Error"].ToString());
                return false;
            }
        }

        private string SendMessage(string uri, FormUrlEncodedContent content)
        {
            HttpClient client = new HttpClient();

            // API service is available only via the TLSv1.2 protocol. This information can be found on https://developers.tme.eu/en/signin 
            ServicePointManager.SecurityProtocol = SecurityProtocolType.Tls12;
            client.DefaultRequestHeaders.Accept.Add(new MediaTypeWithQualityHeaderValue("application/json"));

            try
            {
                HttpResponseMessage response = client.PostAsync(uri, content).Result;
                return response.Content.ReadAsStringAsync().Result;
            }
            catch (Exception ex)
            {
                Console.WriteLine(ex.Message);
                return ex.Message;
            }
        }

        private byte[] HashHmac(string input, string key)
        {
            HMACSHA1 hmac = new HMACSHA1(Encoding.ASCII.GetBytes(key));
            byte[] byteArray = Encoding.ASCII.GetBytes(input);
            return hmac.ComputeHash(byteArray);
        }

        private string UrlEncode(string s)
        {
            // This function uses uppercase in escaped chars to be compatible with the documentation

            // Input: https://api.tme.eu/Products/GetParameters.json
            // https%3a%2f%2fapi.tme.eu%2fProducts%2fGetParameters.json - HttpUtility.UrlEncode
            // https%3A%2F%2Fapi.tme.eu%2FProducts%2FGetParameters.json - result
            // %3a => %3A ...

            char[] temp = System.Web.HttpUtility.UrlEncode(s).ToCharArray();
            for (int i = 0; i < temp.Length - 2; i++)
            {
                if (temp[i] == '%')
                {
                    temp[i + 1] = char.ToUpper(temp[i + 1]);
                    temp[i + 2] = char.ToUpper(temp[i + 2]);
                }
            }
            return new string(temp);
        }
    }
}
