using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using TME_API_CSharp_Example.Objects;

namespace TME_API_CSharp_Example
{
    public class GetParametersJResult : TmeApiJResult<GetParametersJResultData>
    {
        public string GetParametersList()
        {
            StringBuilder sb = new StringBuilder();
            foreach (ProductWithParameters product in this.Data.ProductList)
            {
                foreach (Parameter parameter in product.ParameterList)
                {
                    sb.AppendLine($"\"{product.Symbol}\"; \"{parameter.ParameterName}\"; \"{parameter.ParameterValue}\"");
                }
            }
            return sb.ToString();
        }
    }

    public class GetParametersJResultData
    {
        public string Language { get; set; }
        public ProductWithParameters[] ProductList{ get; set; }
    }
}
