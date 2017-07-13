implementation module cloogl

import Data.Error
import Text

import Data.Functor
import StdArray
import Data.Map
import Internet.HTTP
import Text.Encodings.UrlEncoding

cloogl :: String Int *World -> (MaybeErrorString String, *World)
cloogl url timeout w
# data = concat ["type=regular&url=", urlEncode url, "&token=a"]
# (mer, w) = doHTTPRequest
    { newHTTPRequest
    & req_method = HTTP_POST
    , req_path = "/"
    , server_name = "cloo.gl"
    , server_port = 80
    , req_headers = fromList
      [("Content-Type", "application/x-www-form-urlencoded")
      ,("Content-Length", toString (size data))
      ,("Accept", "*/*")]
    , req_data = data} timeout w
= ((\r->r.rsp_data) <$> mer, w)
