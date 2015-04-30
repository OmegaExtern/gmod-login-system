--[[
-- cvarsx.lua
-- License: The MIT License (MIT) < http://opensource.org/licenses/MIT >
-- Author: OmegaExtern < https://github.com/OmegaExtern > < omegaextern@live.com >
--]]

module("cvarsx", package.seeall)
-- Global/public functions
-- name(string): Name of console variable.
-- default(int): Default value of convar.
-- shouldsave(boolean): Should the ConVar be saved across sessions.
-- userdata(boolean): Should the ConVar and its containing data be sent to the server when it has changed. This make the convar accessible from server using Player:GetInfoNum and similar functions.
-- funcOff(function): The function that is run when convar value changes to 0.
-- funcOn(function): The function that is run when convar value chanes to 1.
-- notify(boolean): Prints client convar change.
-- persist(boolean): Console variable will be reset to the default value if set to false; otherwise it will keep the current value after re-opening/executing a Lua script.
-- forceExecution(boolean): This is new parameter which will force execution of the function for the default state.
CreateTwoStateClientConVar = function(name, default, shouldsave, userdata, funcOff, funcOn, notify, persist, forceExecution)
    local func_name = debug.getinfo(1, "n").name
    assert(type(name) == "string", Format("bad argument %d# to '%s' (string expected, got %s)", 1, func_name, type(name)))
    --print(Format("name=%s", name))
    if not default or type(default) == "nil" then
        default = 0
    elseif type(default) == "string" then
        default = tonumber(default) or 0
    elseif type(default) == "function" then
        default = default()
    end
    assert(type(default) == "number", Format("bad argument %d# to '%s' (number expected, got %s)", 2, func_name, type(default)))
    default = math.Clamp(math.floor(default), 0, 1)
    assert(type(shouldsave) == "boolean", Format("bad argument %d# to '%s' (boolean expected, got %s)", 3, func_name, type(shouldsave)))
    shouldsave = tobool(shouldsave)
    assert(type(userdata) == "boolean", Format("bad argument %d# to '%s' (boolean expected, got %s)", 4, func_name, type(userdata)))
    userdata = tobool(userdata)
    local cvar = CreateClientConVar(name, default, shouldsave, userdata) -- Create client convar.
    -- Remove current(old) callback for the convar (if any).
    local callback = cvars.GetConVarCallbacks(cvar:GetName(), false) -- ConVarName, CreateIfNotFound
    if callback and #callback > 0 then
        cvars.RemoveChangeCallback(cvar:GetName(), cvar:GetName()) -- ConVarName, Identifier
    end
    -- Create a new callback for the convar.
    cvars.AddChangeCallback(cvar:GetName(), function(convar_name, value_old, value_new)
        value_old = math.floor(tonumber(value_old))
        value_new = math.floor(tonumber(value_new) or default)
        if (value_old < 0 and value_new < 0) or (value_old < 0 and value_new == 0) or (value_old == 0 and value_new < 0) or (value_old == 0 and value_new == 0) then
            --print("already off")
            RunConsoleCommand(convar_name, "0")
            return
        end
        if (value_old > 1 and value_new > 1) or (value_old > 1 and value_new == 1) or (value_old == 1 and value_new > 1) or (value_old == 1 and value_new == 1) then
            --print("already on")
            RunConsoleCommand(convar_name, "1")
            return
        end
        if (value_old < 0 and value_new > 1) or (value_old < 0 and value_new == 1) or (value_old == 0 and value_new > 1) or (value_old == 0 and value_new == 1) then
            --print("turn it on")
            if funcOn and type(funcOn) == "function" then
                funcOn(convar_name, value_old, value_new)
            end
            if notify and type(notify) == "boolean" then
                print("Client cvar \"" .. convar_name .. "\" has changed to 1.")
            end
            RunConsoleCommand(convar_name, "1")
            return
        end
        if (value_old > 1 and value_new < 0) or (value_old == 1 and value_new < 0) or (value_old > 1 and value_new == 0) or (value_old == 1 and value_new == 0) then
            --print("turn it off")
            if funcOff and type(funcOff) == "function" then
                funcOff(convar_name, value_old, value_new)
            end
            if notify and type(notify) == "boolean" then
                print("Client cvar \"" .. convar_name .. "\" has changed to 0.")
            end
            RunConsoleCommand(convar_name, "0")
            return
        end
        --print(value_old, value_new)
        RunConsoleCommand(convar_name, tostring(math.Clamp(value_new, 0, 1)))
    end, cvar:GetName()) -- ConVarName, FunctionCallback, Identifier
    -- Persist the current state, else set default state. And execute appropriate function depending on the state.
    if persist and type(persist) == "boolean" then
        local value = math.Clamp(cvar:GetInt(), 0, 1)
        if forceExecution then
            if value == 1 then
                if funcOn and type(funcOn) == "function" then
                    funcOn(cvar:GetName(), value, 1)
                end
                if notify and type(notify) == "boolean" then
                    print("Client cvar \"" .. cvar:GetName() .. "\" has changed to 1.")
                end
            else
                if funcOff and type(funcOff) == "function" then
                    funcOff(cvar:GetName(), value, 0)
                end
                if notify and type(notify) == "boolean" then
                    print("Client cvar \"" .. cvar:GetName() .. "\" has changed to 0.")
                end
            end
        end
        RunConsoleCommand(cvar:GetName(), tostring(value))
    else
        if forceExecution then
            if default == 1 then
                if funcOn and type(funcOn) == "function" then
                    funcOn(cvar:GetName(), cvar:GetInt(), 1)
                end
                if notify and type(notify) == "boolean" then
                    print("Client cvar \"" .. cvar:GetName() .. "\" has changed to 1.")
                end
            else
                if funcOff and type(funcOff) == "function" then
                    funcOff(cvar:GetName(), cvar:GetInt(), 0)
                end
                if notify and type(notify) == "boolean" then
                    print("Client cvar \"" .. cvar:GetName() .. "\" has changed to 0.")
                end
            end
        end
        RunConsoleCommand(cvar:GetName(), tostring(default))
    end
    return cvar
end
collectgarbage()
