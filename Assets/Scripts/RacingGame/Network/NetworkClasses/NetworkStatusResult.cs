using Newtonsoft.Json;

namespace RacingGame.Network.NetworkClasses
{
    public class NetworkStatusResult
    {
        [JsonProperty("Data")]
        public string Data;
    }
}