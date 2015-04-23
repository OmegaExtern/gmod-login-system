--[[
-- cvarsx.lua
-- License: The MIT License (MIT) < http://opensource.org/licenses/MIT >
-- Author: OmegaExtern < https://github.com/OmegaExtern > < omegaextern@live.com >
--]]

module("cvarsx", package.seeall)
-- Global/public functions
CreateTwoStateClientConVar = function(name, default, shouldsave, userdata, funcOff, funcOn, notify, persist, forceExecution)
    local func_name = debug.getinfo(1, "n").name
    assert(type(name) == "string", Format("bad argument %d# to '%s' (string expected, got %s)", 1, func_name, type(name)))
    print(Format("name=%s", name))
    if not default or type(default) == "nil" then
        default = 0
    elseif type(default) == "string" then
        default = tonumber(default) or 0
    elseif type(default) == "function" then
        default = default()
    end
    assert(type(default) == "number", Format("bad argument %d# to '%s' (number expected, got %s)", 2, func_name, type(default)))
    default = math.Clamp(math.floor(default), 0, 1)
    print(Format("default=%d", default))
    shouldsave = tobool(shouldsave and shouldsave or true)
    userdata = tobool(userdata and userdata or false)
    local cvar = CreateClientConVar(name, default, shouldsave, userdata)
    local callback = cvars.GetConVarCallbacks(cvar:GetName(), false) -- ConVarName, CreateIfNotFound
    if callback and #callback > 0 then
        cvars.RemoveChangeCallback(cvar:GetName(), cvar:GetName()) -- ConVarName, Identifier
    end
    cvars.AddChangeCallback(cvar:GetName(), function(convar_name, value_old, value_new)
        value_old = math.floor(tonumber(value_old) or default)
        print(Format("value_old=%d", value_old))
        value_new = math.Clamp(math.floor(tonumber(value_new) or default), 0, 1)
        print(Format("value_new=%d", value_new))
        if math.Clamp(value_old, 0, 1) == value_new then
            RunConsoleCommand(convar_name, value_new)
            return
        end
        print("after clamp check")
        if value_new == 0 then
            print("value_new == 0")
            if funcOff and type(funcOff) == "function" then
                funcOff(convar_name, value_old, value_new)
            end
            if notify and type(notify) == "boolean" then
                print("Client cvar \"" .. convar_name .. "\" has changed to 0.")
            end
        else --if value_new == 1 then
        print("value_new == 1")
            if funcOn and type(funcOn) == "function" then
                funcOn(convar_name, value_old, value_new)
            end
            if notify and type(notify) == "boolean" then
                print("Client cvar \"" .. convar_name .. "\" has changed to 1.")
            end
        end
    end, cvar:GetName()) -- ConVarName, FunctionCallback, Identifier
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
        RunConsoleCommand(cvar:GetName(), value)
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
        RunConsoleCommand(cvar:GetName(), default)
    end
    return cvar
end
collectgarbage()
