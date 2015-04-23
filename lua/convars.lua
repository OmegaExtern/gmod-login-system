--[[
-- convars.lua
-- License: The MIT License (MIT) < http://opensource.org/licenses/MIT >
-- Author: OmegaExtern < https://github.com/OmegaExtern > < omegaextern@live.com >
--]]

if not CLIENT then
    return
end
require("login_system")
require("switch_statement")
login_sys =
{
    sys = login_system,
    case = switch_statement.case,
    --default = switch_statement.default,
    switch = switch_statement.switch,
    chat_prefix = "!",
    convar_prefix = "loginsys_",
    hook_prefix = "login_system_"
}
require("cvarsx")
login_sys.convars = {}
login_sys.convars.enabled = cvarsx.CreateTwoStateClientConVar(login_sys.convar_prefix .. "enabled", 1, false, false, function() -- convar_name, value_old, value_new
hook.Remove("OnPlayerChat", login_sys.hook_prefix .. "OnPlayerChat")
DebugInfo(1, "Login System: Disabled.")
end, function() -- convar_name, value_old, value_new
--[[
GM:OnPlayerChat(Player, string, boolean, boolean)
AVAILABILITY: Client
DESCRIPTION: Called whenever another player send a chat message.
ARGUMENTS:
    (1) Player, ply: The player
    (2) string, text: The players chatted text
    (3) boolean, teamChat: Is the player typing in team chat?
    (4) boolean, isDead: Is the player dead?
RETURNS:
    (1) boolean: Should the message be suppressed?
--]]
--hook.Remove("OnPlayerChat", login_sys.hook_prefix .. "OnPlayerChat")
hook.Add("OnPlayerChat", login_sys.hook_prefix .. "OnPlayerChat", function(ply, text) -- teamChat, isDead
if not login_sys.convars.enabled:GetBool() or not ply:IsValid() then
    return
end
login_sys.temp = text:Trim():gsub("%s+", " ")
if login_sys.temp:Left(1) ~= login_sys.chat_prefix then
    return
end
login_sys.tempe = string.Explode(" ", login_sys.temp)
if login_sys.tempe[1] == login_sys.chat_prefix then
    if #login_sys.tempe < 2 or login_sys.tempe[2]:len() < 5 then
        return
    end
    login_sys.tempe[1] = login_sys.tempe[1] .. login_sys.tempe[2]:upper()
    table.remove(login_sys.tempe, 2)
else
    if #login_sys.tempe < 1 then
        return
    end
    login_sys.tempe[1] = login_sys.tempe[1]:upper()
end
local community_identifier = ply:SteamID64()
print(Format("community_identifier=%s", community_identifier))
print("PrintTable(login_sys):")
PrintTable(login_sys)
login_sys.switch(login_sys.tempe[1],
    login_sys.case(login_sys.chat_prefix .. "LOGIN", function()
        print(Format("login_sys.sys.login(%s):", community_identifier))
        login_sys.sys.login(community_identifier)
    end, true),
    login_sys.case(login_sys.chat_prefix .. "LOGOUT", function()
        print(Format("login_sys.sys.logout(%s):", community_identifier))
        login_sys.sys.logout(community_identifier)
    end, true),
    login_sys.case(login_sys.chat_prefix .. "REGISTER", function()
        local name = (login_sys.tempe[2] and login_sys.tempe[2]) or ""
        print(Format("login_sys.sys.register(%s, %s):", community_identifier, name))
        login_sys.sys.register(community_identifier, name)
        print(name)
    end, true))
end)
DebugInfo(1, "Login System: Enabled.")
end, false, true, true)
login_sys.convars.vgui = cvarsx.CreateTwoStateClientConVar(login_sys.convar_prefix .. "vgui", 0, false, false, nil, function(convar_name) -- value_old, value_new
if not IsValid(GetHUDPanel()) then
    return
end
local dframe = vgui.Create("DFrame", GetHUDPanel())
dframe.btnMaxim:SetVisible(false)
dframe.btnMinim:SetVisible(false)
dframe:SetBackgroundBlur(true)
--dframe:SetDraggable(false)
dframe:SetScreenLock(true)
dframe:SetSize(616, 456)
dframe:SetTitle("Login System VGUI")
dframe:SetZPos(32767)
dframe:Center()
dframe:MakePopup()
dframe.OnClose = function()
    RunConsoleCommand(convar_name, 0)
end
end, false, true, true)
collectgarbage()
