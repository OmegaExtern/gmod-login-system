--[[
-- convars.lua
-- License: The MIT License (MIT) < http://opensource.org/licenses/MIT >
-- Author: OmegaExtern < https://github.com/OmegaExtern > < omegaextern@live.com >
--]]

if not CLIENT then
    return
end
require("login_system") -- require includes/modules/login_system module.
--require("switch_statement") -- require includes/modules/switch_statement module.
-- 'loginsys' is the 'brain' of this addon.
loginsys =
{
    chat_prefix = '!',
    convar_prefix = "loginsys_",
    hook_prefix = "login_system_",
    f =
    {
        login = function(...)
            local funcname = debug.getinfo(1, 'n').name -- login
            local args = { ... }
            assert(#args == 1, Format("%s called with %i parameters, 1 expected", funcname, #args))
            assert(isstring(args[1]), Format("bad argument #%i to '%s' (string expected, got %s)", 1, funcname, type(args[1])))
            print(Format("login_system[%s](%s):", funcname, args[1]))
            login_system[funcname](args[1])
        end,
        logout = function(...)
            local funcname = debug.getinfo(1, 'n').name -- logout
            local args = { ... }
            assert(#args == 1, Format("%s called with %i parameters, 1 expected", funcname, #args))
            assert(isstring(args[1]), Format("bad argument #%i to '%s' (string expected, got %s)", 1, funcname, type(args[1])))
            print(Format("login_system[%s](%s):", funcname, args[1]))
            login_system[funcname](args[1])
        end,
        register = function(...)
            local funcname = debug.getinfo(1, 'n').name -- register
            local args = { ... }
            assert(#args == 2, Format("%s called with %i parameters, 2 expected", funcname, #args))
            assert(isstring(args[1]), Format("bad argument #%i to '%s' (string expected, got %s)", 1, funcname, type(args[1])))
            assert(isstring(args[2]), Format("bad argument #%i to '%s' (string expected, got %s)", 2, funcname, type(args[2])))
            print(Format("login_system[%s](%s, %s):", funcname, args[1], args[2]))
            login_system[funcname](args[1], args[2])
        end
    },
    vgui =
    {
        height = 480.0,
        width = 640.0
    }
}
require("cvarsx") -- require includes/modules/cvarsx module.
cvarsx.SetConVarPrefix(loginsys.convar_prefix)
loginsys.convars = {}
loginsys.convars.vgui = cvarsx.CreateTwoStateClientConVar("vgui", 0, false, false, function()
    if IsValid(loginsys.vgui.dframe) then
        loginsys.vgui.dframe:Close()
    end
end, function(convar_name)
    if not IsValid(GetHUDPanel()) then
        return
    end
    loginsys.vgui.height_multiplier = math.Round(ScrH() / loginsys.vgui.height)
    loginsys.vgui.width_multiplier = math.Round(ScrW() / loginsys.vgui.width)
    loginsys.vgui.dframe = vgui.Create("DFrame", GetHUDPanel())
    loginsys.vgui.dframe.btnMaxim:SetVisible(false) -- Hide maximize button.
    loginsys.vgui.dframe.btnMinim:SetVisible(false) -- Hide minimize button.
    loginsys.vgui.dframe:SetBackgroundBlur(true)
    --loginsys.vgui.dframe:SetDraggable(false)
    loginsys.vgui.dframe:SetScreenLock(true)
    loginsys.vgui.dframe:SetSize(616.0 * loginsys.vgui.width_multiplier, 456.0 * loginsys.vgui.height_multiplier)
    loginsys.vgui.dframe:SetTitle("Login System VGUI")
    loginsys.vgui.dframe:SetZPos(32767.0)
    loginsys.vgui.dframe:Center()
    loginsys.vgui.dframe:MakePopup()
    loginsys.vgui.dframe.OnClose = function()
        RunConsoleCommand(convar_name, '0') -- Reset ConVar.
    end
end, true, true, false)
loginsys.convars.enabled = cvarsx.CreateTwoStateClientConVar("enabled", 0, false, false, function()
    hook.Remove("OnPlayerChat", loginsys.hook_prefix .. "OnPlayerChat")
    hook.Remove("HUDPaint", loginsys.hook_prefix .. "OnScreenResolutionChange")
    hook.Remove("OnScreenResolutionChanged", loginsys.hook_prefix .. "OnScreenResolutionChanged")
    DebugInfo(1, "Login System: Disabled.")
end, function()
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
    hook.Add("OnPlayerChat", loginsys.hook_prefix .. "OnPlayerChat", function(ply, text)
        if not ply:IsValid() then
            -- Exit if player is not valid (it is a console or unconnected player).
            return
        end
        loginsys.temp = text:Trim():gsub("%s+", ' ') -- Trim submitted text and replace all spaces with a single space.
        if loginsys.temp:Left(1) ~= loginsys.chat_prefix then
            -- Exit when first character does not match chat prefix.
            return
        end
        loginsys.tempe = string.Explode(' ', loginsys.temp) -- Explode by space.
        if loginsys.tempe[1] == loginsys.chat_prefix then
            -- In case if the first exploded element matches the chat prefix.
            if #loginsys.tempe < 2 or loginsys.tempe[2]:len() < 5 then
                return
            end
            -- Uppercase and concate second element with the chat prefix (first element).
            loginsys.tempe[1] = loginsys.tempe[1] .. loginsys.tempe[2]:upper()
            -- And remove the second element from the table.
            table.remove(loginsys.tempe, 2)
        else
            -- Assuming the first element starts with chat prefix.
            if #loginsys.tempe < 1 or loginsys.tempe[1]:Left(1) ~= loginsys.chat_prefix then
                return
            end
            -- Uppercase the first element.
            loginsys.tempe[1] = loginsys.tempe[1]:upper()
        end
        local community_identifier = tostring(ply:SteamID64())
        --print(Format("community_identifier=%s", community_identifier))
        --print("PrintTable(loginsys):")
        --PrintTable(loginsys)
        local func = loginsys.f[loginsys.tempe[1]]
        if isfunction(func) then
            func(community_identifier, loginsys.tempe)
        end
        loginsys.temp = nil
        loginsys.tempe = nil
    end)
    hook.Add("HUDPaint", loginsys.hook_prefix .. "OnScreenResolutionChange", function()
        local local_player = LocalPlayer()
        if not IsValid(local_player) then
            return
        end
        local should_draw = hook.Call("HUDShouldDraw", GAMEMODE, loginsys.hook_prefix .. "OnScreenResolutionChange")
        if not should_draw then
            return
        end
        if not local_player.screen_resolution then
            local_player.screen_resolution =
            {
                width = ScrW(),
                height = ScrH()
            }
        end
        local new_width, new_height = ScrW(), ScrH()
        if new_width ~= local_player.screen_resolution.width or new_height ~= local_player.screen_resolution.height then
            hook.Call("OnScreenResolutionChanged", GAMEMODE)
            local_player.screen_resolution.width = new_width
            local_player.screen_resolution.height = new_height
        end
    end)
    hook.Add("OnScreenResolutionChanged", loginsys.hook_prefix .. "OnScreenResolutionChanged", function()
        if not loginsys.convars.vgui or not loginsys.convars.vgui:GetBool() or not IsValid(loginsys.vgui.dframe) then
            return
        end
        loginsys.vgui.dframe:Close()
        RunConsoleCommand(loginsys.convars.vgui:GetName(), '1')
    end)
    DebugInfo(1, "Login System: Enabled.")
end, true, true, false)
collectgarbage()