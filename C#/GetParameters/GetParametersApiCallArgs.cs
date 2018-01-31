using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using TME_API_CSharp_Example.ApiCore;
using TME_API_CSharp_Example.Objects;

namespace TME_API_CSharp_Example
{
    public class GetParametersApiCallArgs
    {
        public string Country { get; private set; }
        public string Language { get; private set; }
        public string[] SymbolList { get; private set; }

        public GetParametersApiCallArgs(string country, string language, params string[] symbolList)
        {
            this.Country = country;
            this.Language = language;
            this.SymbolList = symbolList;
        }

        public Dictionary<string, string> BuildApiParams()
        {
            // Dictionary must be ordered by keys
            Dictionary<string, string> apiParams = new Dictionary<string, string>();
            apiParams.Add("Country", this.Country);
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
