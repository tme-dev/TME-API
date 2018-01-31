using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace TME_API_CSharp_Example.Objects
{
    public class ErrorJResult
    {
        public string Status { get; private set; }
        public string Error { get; private set; }

        public ErrorJResult(string status, string errorMessage)
        {
            this.Status = status;
            this.Error = errorMessage;
        }
    }
}
