module("login_system", package.seeall)
-- Local/private functions & variables
local URL, METHOD = "http://localhost:63342/gmod-login-system/main.php", "post"
-- Global/public functions
login = function(community_identifier)
    HTTP(
        {
            failed = function(reason)
                error(reason)
            end,
            success = function(code, body)
                print(body)
            end,
            method = METHOD,
            url = URL,
            parameters =
            {
                ["community_identifier"] = community_identifier
            }
        })
end
logout = function(community_identifier)
    HTTP(
        {
            failed = function(reason)
                error(reason)
            end,
            success = function(code, body)
                print(body)
            end,
            method = METHOD,
            url = URL,
            parameters =
            {
                ["community_identifier"] = community_identifier
            }
        })
end
register = function(community_identifier, name)
    HTTP(
        {
            failed = function(reason)
                error(reason)
            end,
            success = function(code, body)
                print(body)
            end,
            method = METHOD,
            url = URL,
            parameters =
            {
                ["community_identifier"] = community_identifier,
                ["name"] = name
            }
        })
end
collectgarbage()