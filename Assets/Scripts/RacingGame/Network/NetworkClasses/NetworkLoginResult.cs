using System.Collections.Generic;
using Newtonsoft.Json;

namespace RacingGame.Network.NetworkClasses
{
    public class NetworkLoginResult
    {
        [JsonProperty("ID")]
        public int ID;

        [JsonProperty("Status")]
        public string Status;

        [JsonProperty("Mail")]
        public string Mail;

        [JsonProperty("Name")]
        public string Name;

        [JsonProperty("Companies")]
        public List<NetworkCompany> Companies;
    }
}