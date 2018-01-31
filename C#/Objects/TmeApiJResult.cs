using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace TME_API_CSharp_Example.Objects
{
    public class TmeApiJResult<DataType>
    {
        public string Status { get; set; }
        public DataType Data { get; set; }
    }
}
