using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace TME_API_CSharp_Example.Objects
{
    public class ProductWithParameters
    {
        public string Symbol { get; set; }
        public Parameter[] ParameterList { get; set; }
    }
}
