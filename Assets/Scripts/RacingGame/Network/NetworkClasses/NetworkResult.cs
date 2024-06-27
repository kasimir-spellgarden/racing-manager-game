using System;
using Newtonsoft.Json;
using UnityEngine;

namespace RacingGame.Network.NetworkClasses
{
    public class NetworkResult<T> where T : class
    {
        [JsonProperty("Result")]
        public T Result;

        [JsonProperty("Info")]
        public NetworkInfo info;

        [JsonProperty("Status")]
        public string Status;

        public static NetworkResult<T> FromText(string text)
        {
            try
            {
                return JsonConvert.DeserializeObject<NetworkResult<T>>(text);
            }
            catch (Exception e)
            {
                Debug.LogException(e);
                return null;
            }
        }
    }
}