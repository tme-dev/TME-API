using Newtonsoft.Json;
using Newtonsoft.Json.Linq;
using System;
using System.Collections.Generic;
using System.Linq;
using TME_API_CSharp_Example.ApiCore;
using TME_API_CSharp_Example.Objects;

namespace TME_API_CSharp_Example
{
    class Program
    {
        static void Main(string[] args)
        {
            TmeApi tmeApi = new TmeApi();
            try
            {
                // Easy example (dictionary must be ordered by keys)
                Dictionary<string, string> apiParams = new Dictionary<string, string>()
                {
                    { "Country", "GB" },
                    { "Language", "EN"},
                    { "SymbolList[0]", "1N4007" },
                    { "SymbolList[1]", "1/4W1.1M" },
                    { "Token", ApiCredentials.Token },
                };
                string jsonContent = tmeApi.ApiCall("Products/GetParameters", apiParams);

                // Parsing whole .json from content returned by API
                // JObject json = JObject.Parse(jsonContent);
                // Console.WriteLine(json.ToString());
                // Console.WriteLine();

                // Another example with using objects
                Console.WriteLine("Call api action: Products/GetParameters then parse .json and show result as list in format: \"ProductSymbol\"; \"ParameterName\"; \"ParameterValue\"");
                Console.WriteLine();

                // Created class to easily pass arguments
                GetParametersApiCallArgs getProductsArgs = new GetParametersApiCallArgs("GB", "EN", "1N4007", "1/4W1.1M");
                jsonContent = tmeApi.ApiCall("Products/GetParameters", getProductsArgs.BuildApiParams());

                // Checking result
                if (IsStatusOK(tmeApi, jsonContent))
                {
                    // Another way to parse .json
                    // Created class which is equivalent of .json structure depending on documentation https://developers.tme.eu/en/
                    GetParametersJResult result = JsonConvert.DeserializeObject<GetParametersJResult>(jsonContent);

                    // Some function to show easy manipulation on data in custom class
                    Console.WriteLine(result.GetParametersList());
                }

                // Example with Products/GetPrices using objects
                Console.WriteLine("Call api action: Products/GetPrices then parse .json and show result");
                Console.WriteLine();

                GetPricesApiCallArgs getPricesArgs = new GetPricesApiCallArgs("GB", "EN", "EUR", "1N4007", "1/4W1.1M");
                jsonContent = tmeApi.ApiCall("Products/GetPrices", getPricesArgs.BuildApiParams());

                if (IsStatusOK(tmeApi, jsonContent))
                {
                    // GetPricesJResult result = JsonConvert.DeserializeObject<GetPricesJResult>(jsonContent);

                    JObject json = JObject.Parse(jsonContent);
                    Console.WriteLine(json.ToString());
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine(ex.Message);
            }

            Console.ReadLine();
        }

        private static bool IsStatusOK(TmeApi tmeApi, string jsonContent)
        {
            ErrorJResult errorResult = null;
            if (!tmeApi.IsStatusOK(jsonContent, out errorResult))
            {
                Console.WriteLine($"Api returns error:");
                Console.WriteLine();
                Console.WriteLine($"Status: {errorResult.Status}");
                Console.WriteLine($"Error: {errorResult.Error}");
                return false;
            }

            return true;
        }
    }
}
