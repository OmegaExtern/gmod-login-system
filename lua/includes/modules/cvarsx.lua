--[[
-- cvarsx.lua
-- License: The MIT License (MIT) < http://opensource.org/licenses/MIT >
-- Author: OmegaExtern < https://github.com/OmegaExtern > < omegaextern@live.com >
--]]

module("cvarsx", package.seeall)
-- Local/private variables
local convar_name_prefix = ""

-- Global/public functions
GetConVarPrefix = function()
    return convar_name_prefix
end
SetConVarPrefix = function(prefix)
    local func_name = debug.getinfo(1, 'n').name
    assert(isstring(prefix) and prefix:len() < 33, Format("bad argument %d# to '%s' (string expected, got %s)", 1, func_name, type(prefix)))
    convar_name_prefix = prefix
end

--[[
cvarsx.CreateTwoStateClientConVar(string, number, boolean, boolean, function, function, boolean, boolean, boolean)
AVAILABILITY: Client
DESCRIPTION: Creates a two-state client-side console variable.
ARGUMENTS:
    (1) string, name: Name of the ConVar to be created and able to be accessed.
    (2) number, default: Default value of the ConVar.
    (3) boolean, shouldsave: Should the ConVar be saved across sessions?
    (4) boolean, userdata: Should the ConVar and its containing data be sent to the server when it has changed? This make the ConVar accessible from server using Player:GetInfoNum and similar functions.
    (5) function([string, convar_name], [number, value_old], [number, value_new]), funcOff: The function that is run when the ConVar changes to 0. Parameters are optional, first returns name of the ConVar, second returns an old value, third returns the new value.
    (6) function([string, convar_name], [number, value_old], [number, value_new]), funcOn: The function that is run when the ConVar changes to 1. Parameters are optional, first returns name of the ConVar, second returns an old value, third returns the new value.
    (7) boolean, notify: Should it print notification into console when named ConVar changes?
    (8) boolean, persist: If set to false, ConVar will be reset at the default value after re-opening/executing a script; otherwise it will keep the current value.
    (9) boolean, forceExecution: Should it execute the current state function upon creating a ConVar regardless of the two-state rule?
RETURNS:
    (1) ConVar: The ConVar object.
--]]
CreateTwoStateClientConVar = function(name, default, shouldsave, userdata, funcOff, funcOn, notify, persist, forceExecution)
    if not CLIENT then
        return
    end
    local func_name = debug.getinfo(1, 'n').name
    assert(isstring(name) and name:len() > 1, Format("bad argument %d# to '%s' (string expected, got %s)", 1, func_name, type(name)))
    if not default or type(default) == "nil" then
        default = 0
    elseif isstring(default) then
        default = tonumber(default) or 0
    elseif isfunction(default) then
        default = default()
    end
    assert(isnumber(default), Format("bad argument %d# to '%s' (number expected, got %s)", 2, func_name, type(default)))
    default = math.Clamp(math.floor(default), 0, 1)
    assert(isbool(shouldsave), Format("bad argument %d# to '%s' (boolean expected, got %s)", 3, func_name, type(shouldsave)))
    assert(isbool(userdata), Format("bad argument %d# to '%s' (boolean expected, got %s)", 4, func_name, type(userdata)))
    if convar_name_prefix:len() > 0 then
        name = convar_name_prefix .. name
    end
    local cvar = CreateClientConVar(name, default, shouldsave, userdata) -- Create a client-side ConVar.
    -- Remove current/old ConVar callback(s) (if any).
    --if table.Count(cvars.GetConVarCallbacks(cvar:GetName(), false)) > 0 then
    cvars.RemoveChangeCallback(cvar:GetName(), cvar:GetName()) -- ConVarName, Identifier
    table.Empty(cvars.GetConVarCallbacks(cvar:GetName(), false)) -- ConVarName, CreateIfNotFound
    --end
    -- Create a new callback for the convar.
    cvars.AddChangeCallback(cvar:GetName(), function(convar_name, value_old, value_new)
        value_old = math.floor(tonumber(value_old) or -1)
        value_new = math.floor(tonumber(value_new) or -1)
        if (value_old < 0 and value_new < 0) or (value_old < 0 and value_new == 0) or (value_old == 0 and value_new < 0) or (value_old == 0 and value_new == 0) then
            -- Already off.
            RunConsoleCommand(convar_name, '0')
            return
        end
        if (value_old > 1 and value_new > 1) or (value_old > 1 and value_new == 1) or (value_old == 1 and value_new > 1) or (value_old == 1 and value_new == 1) then
            -- Already on.
            RunConsoleCommand(convar_name, '1')
            return
        end
        if (value_old < 0 and value_new > 1) or (value_old < 0 and value_new == 1) or (value_old == 0 and value_new > 1) or (value_old == 0 and value_new == 1) then
            -- Turn it on.
            if isfunction(funcOn) then
                funcOn(convar_name, value_old, value_new)
            end
            if isbool(notify) and notify then
                print(Format("Client cvar \"%s\" has changed to 1.", convar_name))
            end
            RunConsoleCommand(convar_name, '1')
            return
        end
        if (value_old > 1 and value_new < 0) or (value_old == 1 and value_new < 0) or (value_old > 1 and value_new == 0) or (value_old == 1 and value_new == 0) then
            -- Turn it off.
            if isfunction(funcOff) then
                funcOff(convar_name, value_old, value_new)
            end
            if isbool(notify) and notify then
                print(Format("Client cvar \"%s\" has changed to 0.", convar_name))
            end
            RunConsoleCommand(convar_name, '0')
            return
        end
        RunConsoleCommand(convar_name, tostring(math.Clamp(value_new, 0, 1)))
    end, cvar:GetName()) -- ConVarName, FunctionCallback, Identifier
    if isbool(persist) and persist then
        local value = math.Clamp(cvar:GetInt(), 0, 1)
        if forceExecution then
            if value == 1 then
                if isfunction(funcOn) then
                    funcOn(cvar:GetName(), value, 1)
                end
                if isbool(notify) and notify then
                    print(Format("Client cvar \"%s\" has changed to 1.", cvar:GetName()))
                end
            else
                if isfunction(funcOff) then
                    funcOff(cvar:GetName(), value, 0)
                end
                if isbool(notify) and notify then
                    print(Format("Client cvar \"%s\" has changed to 0.", cvar:GetName()))
                end
            end
        end
        RunConsoleCommand(cvar:GetName(), tostring(value))
    else
        if forceExecution then
            if default == 1 then
                if isfunction(funcOn) then
                    funcOn(cvar:GetName(), cvar:GetInt(), 1)
                end
                if isbool(notify) and notify then
                    print(Format("Client cvar \"%s\" has changed to 1.", cvar:GetName()))
                end
            else
                if isfunction(funcOff) then
                    funcOff(cvar:GetName(), cvar:GetInt(), 0)
                end
                if isbool(notify) and notify then
                    print(Format("Client cvar \"%s\" has changed to 0.", cvar:GetName()))
                end
            end
        end
        RunConsoleCommand(cvar:GetName(), tostring(default))
    end
    return cvar -- ConVar
end
collectgarbage()
