using Spellgarden.Plugins;

namespace RacingGame.Savegame
{
    public class UserSave : SerializedClass<UserSave>
    {
        public string server = "http://manager-game.kasimir-blust.de";
        public string username;
        public string password;
    }
}