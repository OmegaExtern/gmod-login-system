module("login_system", package.seeall)
-- Local/private functions & variables
local URL, METHOD = "http://localhost:63342/gmod-login-system/main.php", "post"
-- Global/public functions
login = function(community_identifier)
    HTTP(
        {
            failed = function(reason)
                error("login: " .. reason)
            end,
            success = function(code, body)
                local bodye = body:Explode("<br>")
                PrintTable(CompileString(bodye[1], debug.getinfo(1, "S").source:sub(2))())
                print(bodye[2])
            end,
            method = METHOD,
            url = URL,
            parameters =
            {
                ["do"] = "LOGIN",
                ["community_identifier"] = community_identifier
            }
        })
end
logout = function(community_identifier)
    HTTP(
        {
            failed = function(reason)
                error("logout: " .. reason)
            end,
            success = function(code, body)
                local bodye = body:Explode("<br>")
                PrintTable(CompileString(bodye[1], debug.getinfo(1, "S").source:sub(2))())
                print(bodye[2])
            end,
            method = METHOD,
            url = URL,
            parameters =
            {
                ["do"] = "LOGOUT",
                ["community_identifier"] = community_identifier
            }
        })
end
register = function(community_identifier, name)
    HTTP(
        {
            failed = function(reason)
                error("register: " .. reason)
            end,
            success = function(code, body)
                local bodye = body:Explode("<br>")
                PrintTable(CompileString(bodye[1], debug.getinfo(1, "S").source:sub(2))())
                print(bodye[2])
            end,
            method = METHOD,
            url = URL,
            parameters =
            {
                ["do"] = "REGISTER",
                ["community_identifier"] = community_identifier,
                ["name"] = name
            }
        })
end
collectgarbage()
