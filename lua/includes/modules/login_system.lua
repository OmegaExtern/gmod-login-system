--[[
-- login_system.lua
-- License: The MIT License (MIT) < http://opensource.org/licenses/MIT >
-- Author: OmegaExtern < https://github.com/OmegaExtern > < omegaextern@live.com >
--]]

module("login_system", package.seeall)
-- Local/private variables
local URL, METHOD = "http://localhost:63342/gmod-login-system/main.php", "post"

-- Global/public functions
login = function(community_identifier)
    local func_name = debug.getinfo(1, 'n').name
    print(Format("func_name=%s", func_name))
    assert(isstring(community_identifier) and string.match(community_identifier, "^7656119%d%d%d%d%d%d%d%d%d%d$") ~= nil, Format("bad argument %d# to '%s' (string expected, got %s)", 1, func_name, type(community_identifier)))
    --community_identifier = Format("%s", community_identifier)
    print(Format("community_identifier=%s", community_identifier))
    HTTP({
        failed = function(reason)
            error(Format("%s(%s): %s", func_name, community_identifier, reason))
        end,
        success = function(code, body)
            print(Format("code=%d", code))
            print(Format("body=%s", body))
            if body:lower():StartWith("error") then
                print(body)
                return
            end
            local bodye = string.Explode("<br>", body)
            print("PrintTable(bodye):")
            PrintTable(bodye)
            local succ, ret = pcall(CompileString(bodye[1], debug.getinfo(1, 'S').source:sub(2)))
            if not succ then
                error(Format("%s(%s): %s", func_name, community_identifier, ret))
            end
            print("PrintTable(ret):")
            PrintTable(ret)
        end,
        method = METHOD,
        url = URL,
        parameters =
        {
            ["do"] = func_name:upper(),
            ["community_identifier"] = community_identifier
        },
        headers =
        {
            ["cookies"] =
            {
                ["XDEBUG_SESSION"] = "PHPSTORM"
            }
        }
    })
end
logout = function(community_identifier)
    local func_name = debug.getinfo(1, 'n').name
    print(Format("func_name=%s", func_name))
    assert(isstring(community_identifier) and string.match(community_identifier, "^7656119%d%d%d%d%d%d%d%d%d%d$") ~= nil, Format("bad argument %d# to '%s' (string expected, got %s)", 1, func_name, type(community_identifier)))
    --community_identifier = Format("%s", community_identifier)
    print(Format("community_identifier=%s", community_identifier))
    HTTP({
        failed = function(reason)
            error(Format("%s(%s): %s", func_name, community_identifier, reason))
        end,
        success = function(code, body)
            print(Format("code=%d", code))
            print(Format("body=%s", body))
            if body:lower():StartWith("error") then
                print(body)
                return
            end
            local bodye = string.Explode("<br>", body)
            print("PrintTable(bodye):")
            PrintTable(bodye)
            local succ, ret = pcall(CompileString(bodye[1], debug.getinfo(1, 'S').source:sub(2)))
            if not succ then
                error(Format("%s(%s): %s", func_name, community_identifier, ret))
            end
            print("PrintTable(ret):")
            PrintTable(ret)
        end,
        method = METHOD,
        url = URL,
        parameters =
        {
            ["do"] = func_name:upper(),
            ["community_identifier"] = community_identifier
        },
        headers =
        {
            ["cookies"] =
            {
                ["XDEBUG_SESSION"] = "PHPSTORM"
            }
        }
    })
end
register = function(community_identifier, name)
    local func_name = debug.getinfo(1, 'n').name
    print(Format("func_name=%s", func_name))
    assert(isstring(community_identifier) and string.match(community_identifier, "^7656119%d%d%d%d%d%d%d%d%d%d$") ~= nil, Format("bad argument %d# to '%s' (string expected, got %s)", 1, func_name, type(community_identifier)))
    --community_identifier = Format("%s", community_identifier)
    print(Format("community_identifier=%s", community_identifier))
    assert(isstring(name), Format("bad argument %d# to '%s' (string expected, got %s)", 2, func_name, type(name)))
    HTTP({
        failed = function(reason)
            error(Format("%s(%s): %s", func_name, community_identifier, reason))
        end,
        success = function(code, body)
            print(Format("code=%d", code))
            print(Format("body=%s", body))
            if body:lower():StartWith("error") then
                print(body)
                return
            end
            local bodye = string.Explode("<br>", body)
            print("PrintTable(bodye):")
            PrintTable(bodye)
            local succ, ret = pcall(CompileString(bodye[1], debug.getinfo(1, 'S').source:sub(2)))
            if not succ then
                error(Format("%s(%s, %s): %s", func_name, community_identifier, name, ret))
            end
            print("PrintTable(ret):")
            PrintTable(ret)
        end,
        method = METHOD,
        url = URL,
        parameters =
        {
            ["do"] = func_name:upper(),
            ["community_identifier"] = community_identifier,
            ["name"] = name
        },
        headers =
        {
            ["cookies"] =
            {
                ["XDEBUG_SESSION"] = "PHPSTORM"
            }
        }
    })
end
collectgarbage()
