using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace TME_API_CSharp_Example.Objects
{
    public class ProductWithPrices
    {
        public string Symbol { get; set; }
        public string Unit { get; set; }
        public int VatRate { get; set; }
        public string VatType { get; set; }
        public Price[] PriceList { get; set; }
    }
}
