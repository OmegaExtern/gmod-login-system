module("login_system", package.seeall)
-- Local/private functions & variables
local URL, METHOD = "http://localhost:63342/gmod-login-system/main.php", "post"
-- Global/public functions
login = function(community_identifier)
    local func_name = debug.getinfo(1, "n").name
    print(Format("func_name=%s", func_name))
    community_identifier = Format("%s", community_identifier)
    print(Format("community_identifier=%s", community_identifier))
    HTTP({
        failed = function(reason)
            error(Format("%s(%s): %s", func_name, community_identifier, reason))
        end,
        success = function(code, body)
            print(Format("code=%d", code))
            print(Format("body=%s", body))
            local bodye = string.Explode("<br>", body)
            print("PrintTable(bodye):")
            PrintTable(bodye)
            local succ, ret = pcall(CompileString(bodye[1], string.sub(debug.getinfo(1, "S").source, 2)))
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
            ["do"] = string.upper(func_name),
            ["community_identifier"] = community_identifier
        }
    })
end
logout = function(community_identifier)
    local func_name = debug.getinfo(1, "n").name
    print(Format("func_name=%s", func_name))
    community_identifier = Format("%s", community_identifier)
    print(Format("community_identifier=%s", community_identifier))
    HTTP({
        failed = function(reason)
            error(Format("%s(%s): %s", func_name, community_identifier, reason))
        end,
        success = function(code, body)
            print(Format("code=%d", code))
            print(Format("body=%s", body))
            local bodye = string.Explode("<br>", body)
            print("PrintTable(bodye):")
            PrintTable(bodye)
            local succ, ret = pcall(CompileString(bodye[1], string.sub(debug.getinfo(1, "S").source, 2)))
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
            ["do"] = string.upper(func_name),
            ["community_identifier"] = community_identifier
        }
    })
end
register = function(community_identifier, name)
    local func_name = debug.getinfo(1, "n").name
    print(Format("func_name=%s", func_name))
    community_identifier = Format("%s", community_identifier)
    print(Format("community_identifier=%s", community_identifier))
    HTTP({
        failed = function(reason)
            error(Format("%s(%s): %s", func_name, community_identifier, reason))
        end,
        success = function(code, body)
            print(Format("code=%d", code))
            print(Format("body=%s", body))
            local bodye = string.Explode("<br>", body)
            print("PrintTable(bodye):")
            PrintTable(bodye)
            local succ, ret = pcall(CompileString(bodye[1], string.sub(debug.getinfo(1, "S").source, 2)))
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
            ["do"] = string.upper(func_name),
            ["community_identifier"] = community_identifier,
            ["name"] = name
        }
    })
end
collectgarbage()
