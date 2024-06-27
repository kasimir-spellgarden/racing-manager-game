using System.Collections.Generic;
using Newtonsoft.Json;

namespace RacingGame.Network.NetworkClasses
{
    public class NetworkInfo
    {
        [JsonProperty("Version")]
        public string Version;

        [JsonProperty("Time")]
        public int Time;

        [JsonProperty("Request")]
        public List<string> Request;
    }
}