--[[
-- switch_statement.lua
-- License: The MIT License (MIT) < http://opensource.org/licenses/MIT >
-- Author: OmegaExtern < https://github.com/OmegaExtern > < omegaextern@live.com >
--]]

module("switch_statement", package.seeall)
-- Global/public functions
case = function(case, func, breakCase)
    assert(type(case) ~= "nil", string.format("argument %d# (case) is %s, any expected", 1, type(case)))
    assert(type(func) == "function", string.format("argument %d# (func) is %s, function expected", 2, type(func)))
    assert(type(breakCase) == "boolean", string.format("argument %d# (breakCase) is %s, boolean expected", 3, type(breakCase)))
    return { CASE = case, CASE_FUNC = func, BREAK_CASE = breakCase }
end
default = function(func)
    assert(type(func) == "function", string.format("argument %d# (func) is %s, function expected", 1, type(func)))
    return { DEFAULT_CASE = true, DEFAULT_FUNC = func }
end
switch = function(case, ...)
    --assert(type(case) ~= "nil", string.format("argument %d# (case) is %s, any expected", 1, type(case)))
    local args = { ... }
    assert(#args > 0, "Invalid number of cases, at least one expected.")
    for k, v in pairs(args) do
        assert(type(v) == "table", string.format("Invalid case-statement (key %d).", k))
        if type(v.CASE) ~= "nil" and type(v.CASE_FUNC) == "function" then
            if v.CASE == case then
                v.CASE_FUNC()
                if type(v.BREAK_CASE) == "boolean" and v.BREAK_CASE then
                    break
                end
            end
        elseif type(v.DEFAULT_CASE) == "boolean" and v.DEFAULT_CASE and type(v.DEFAULT_FUNC) == "function" then
            v.DEFAULT_FUNC()
            break
        else
            error("Invalid switch-statement.")
        end
    end
end
collectgarbage()