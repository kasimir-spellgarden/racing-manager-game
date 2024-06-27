using Newtonsoft.Json;

namespace RacingGame.Network.NetworkClasses
{
    public class NetworkCompany
    {
        [JsonProperty("ID")]
        public int ID;

        [JsonProperty("UserID")]
        public int UserID;

        [JsonProperty("Name")]
        public string Name;

        [JsonProperty("Funds")]
        public int Funds;
    }
}