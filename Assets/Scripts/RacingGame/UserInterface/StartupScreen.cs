using System.Collections;
using RacingGame.Management;
using RacingGame.Network.NetworkClasses;

namespace RacingGame.UserInterface
{
    public class StartupScreen : GameScreen
    {
        public override void Open()
        {
            StartCoroutine(Startup());
        }

        private IEnumerator Startup()
        {
            NetworkResult<NetworkStatusResult> startupResult;
            yield return GameManager.Instance.networkManager.Initialize(arg0 => startupResult = arg0);
        }
        
    }
}