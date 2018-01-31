using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using TME_API_CSharp_Example.ApiCore;

namespace TME_API_CSharp_Example
{
    public class GetPricesApiCallArgs
    {
        public string Country { get; private set; }
        public string Language { get; private set; }
        public string Currency { get; private set; }
        public string[] SymbolList { get; private set; }

        public GetPricesApiCallArgs(string country, string language, string currency, params string[] symbolList)
        {
            this.Country = country;
            this.Language = language;
            this.Currency = currency;
            this.SymbolList = symbolList;
        }

        public Dictionary<string, string> BuildApiParams()
        {
            // Dictionary must be ordered by keys
            Dictionary<string, string> apiParams = new Dictionary<string, string>();
            apiParams.Add("Country", this.Country);
            apiParams.Add("Currency", this.Currency);
            apiParams.Add("Language", this.Language);

            for (int i = 0; i < this.SymbolList.Length; i++)
            {
                apiParams.Add($"SymbolList[{i}]", this.SymbolList[i]);
            }

            apiParams.Add("Token", ApiCredentials.Token);
            return apiParams;
        }
    }
}
