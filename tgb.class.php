<?php
class TG
{
	private static $ch;
	static function start()
		{
			self::$ch = curl_init();
			curl_setopt_array(self::$ch, array( CURLOPT_FOLLOWLOCATION => false, CURLOPT_RETURNTRANSFER => true, CURLOPT_SSL_VERIFYPEER => false, CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.3; rv:38.0) Gecko/20100101 Firefox/38.0', CURLOPT_POST => true, CURLOPT_HTTPHEADER, array('Host' => 'api.telegram.org')));
		}
	static function setWebhook($tok = BOT_TOKEN, $path)
		{
			curl_setopt(self::$ch, CURLOPT_URL, 'https://api.telegram.org/bot' .$tok .'/setWebhook');
			curl_setopt(self::$ch, CURLOPT_POSTFIELDS, 'url=' .$path);
			return curl_exec(self::$ch);
		}
	static function sendMessage($msg, $chat_id, $keyboard, $msg_id, $pars, $notify)
		{
			self::sendAction('typing',$chat_id);
			if($msg_id)
				$mass['reply_to_message_id'] = $msg_id;
			if(is_array($keyboard)){
				$keys = $keyboard;
				if($keys['keyboard'])
					$keys['resize_keyboard'] = true;
			}else{
				$keys['hide_keyboard'] = true;
				$keys['selective'] = true;
			}
			if($pars)
				$mass['parse_mode'] = 'markdown';
			if($pars == 2)
				$mass['parse_mode'] = 'html';
			if($notify)
				$mass['disable_notification'] = true;
			$mass['reply_markup'] = json_encode($keys);
			$mass['chat_id'] = $chat_id;
			$mass['text'] = $msg;
			$mass['disable_web_page_preview'] = true;
			curl_setopt(self::$ch, CURLOPT_URL, API_URL .'sendMessage');
			curl_setopt(self::$ch, CURLOPT_POSTFIELDS, http_build_query($mass));
			return curl_exec(self::$ch);
		}
	static function answerCallbackQuery($msg, $call_id, $notify)
		{
			$mass['callback_query_id'] = $call_id;
			$mass['text'] = $msg;
			$mass['show_alert'] = $notify;
			curl_setopt(self::$ch, CURLOPT_URL, API_URL .'answerCallbackQuery');
			curl_setopt(self::$ch, CURLOPT_POSTFIELDS, http_build_query($mass));
			return curl_exec(self::$ch);
		}
	static function editMessageText($msg, $chat_id, $keyboard, $msg_id, $pars)
		{
			$mass['message_id'] = $msg_id;
			if(is_array($keyboard)){
				$keys = $keyboard;
				$mass['reply_markup'] = json_encode($keys);
				//$keys['resize_keyboard'] = true;
			}
			if($pars)
				$mass['parse_mode'] = 'markdown';
			if($pars == 2)
				$mass['parse_mode'] = 'html';
			$mass['chat_id'] = $chat_id;
			$mass['text'] = $msg;
			$mass['disable_web_page_preview'] = true;
			curl_setopt(self::$ch, CURLOPT_URL, API_URL .'editMessageText');
			curl_setopt(self::$ch, CURLOPT_POSTFIELDS, http_build_query($mass));
			return curl_exec(self::$ch);
		}
	static function editMessageReplyMarkup($chat_id, $keyboard, $msg_id)
		{
			if($msg_id)
				$mass['message_id'] = $msg_id;
			if(is_array($keyboard)){
				$keys = $keyboard;
				$mass['reply_markup'] = json_encode($keys);
			}
			$mass['chat_id'] = $chat_id;
			curl_setopt(self::$ch, CURLOPT_URL, API_URL .'editMessageText');
			curl_setopt(self::$ch, CURLOPT_POSTFIELDS, http_build_query($mass));
			return curl_exec(self::$ch);
		}
	static function editMessageCaption($msg, $chat_id, $keyboard, $msg_id)
		{
			if($msg_id)
				$mass['message_id'] = $msg_id;
			if(is_array($keyboard)){
				$keys = $keyboard;
				$mass['reply_markup'] = json_encode($keys);
			}
			$mass['chat_id'] = $chat_id;
			$mass['caption'] = $msg;
			curl_setopt(self::$ch, CURLOPT_URL, API_URL .'editMessageText');
			curl_setopt(self::$ch, CURLOPT_POSTFIELDS, http_build_query($mass));
			return curl_exec(self::$ch);
		}
	static function sendPhoto($msg, $chat_id, $photo, $keys)
		{
			self::sendAction('upload_photo',$chat_id);
			$mass['chat_id'] = $chat_id;
			$mass['caption'] = $msg;
			$mass['photo'] = $photo;
			if($keys){
				$mass['reply_markup'] = json_encode($keys);
			}
			curl_setopt(self::$ch, CURLOPT_URL, API_URL .'sendPhoto');
			curl_setopt(self::$ch, CURLOPT_POSTFIELDS, http_build_query($mass));
			return curl_exec(self::$ch);
		}
	static function inline($id, $json)
		{
			$mass['inline_query_id'] = $id;
			$mass['results'] = $json;
			$mass['cache_time'] = 0;
			$mass['is_personal'] = true;
			curl_setopt(self::$ch, CURLOPT_URL, API_URL .'answerInlineQuery');
			curl_setopt(self::$ch, CURLOPT_POSTFIELDS, http_build_query($mass));
			return curl_exec(self::$ch);
		}
	static function sendAction($action, $chat_id)
		{
			return true;
			$mass['chat_id'] = $chat_id;
			$mass['action'] = $action;
			//$mass['photo'] = $photo;
			curl_setopt(self::$ch, CURLOPT_URL, API_URL .'sendChatAction');
			curl_setopt(self::$ch, CURLOPT_POSTFIELDS, http_build_query($mass));
			return curl_exec(self::$ch);
		}
	static function sendSticker($chat_id, $sticker)
		{
			//self::sendAction('typing',$chat_id);
			$mass['chat_id'] = $chat_id;
			//$mass['caption'] = $msg;
			$mass['sticker'] = $sticker;
			curl_setopt(self::$ch, CURLOPT_URL, API_URL .'sendSticker');
			curl_setopt(self::$ch, CURLOPT_POSTFIELDS, http_build_query($mass));
			return curl_exec(self::$ch);
		}
	static function sendVoice($chat_id, $voice)
		{
			self::sendAction('upload_audio',$chat_id);
			$mass['chat_id'] = $chat_id;
			//$mass['caption'] = $msg;
			$mass['voice'] = $voice;
			curl_setopt(self::$ch, CURLOPT_URL, API_URL .'sendVoice');
			curl_setopt(self::$ch, CURLOPT_POSTFIELDS, http_build_query($mass));
			return curl_exec(self::$ch);
		}
	static function kickUser($chat_id, $user_id = self_id)
		{
			$mass['chat_id'] = $chat_id;
			$mass['user_id'] = $user_id;
			curl_setopt(self::$ch, CURLOPT_URL, API_URL .'kickChatMember');
			curl_setopt(self::$ch, CURLOPT_POSTFIELDS, http_build_query($mass));
			return curl_exec(self::$ch);
		}
	static function unbanUser($chat_id, $user_id)
		{
			$mass['chat_id'] = $chat_id;
			$mass['user_id'] = $user_id;
			curl_setopt(self::$ch, CURLOPT_URL, API_URL .'unbanChatMember');
			curl_setopt(self::$ch, CURLOPT_POSTFIELDS, http_build_query($mass));
			return curl_exec(self::$ch);
		}
	static function sendAudio($chat_id, $audio)
		{
			self::sendAction('upload_audio',$chat_id);
			$mass['chat_id'] = $chat_id;
			//$mass['caption'] = $msg;
			$mass['audio'] = $audio;
			curl_setopt(self::$ch, CURLOPT_URL, API_URL .'sendAudio');
			curl_setopt(self::$ch, CURLOPT_POSTFIELDS, http_build_query($mass));
			return curl_exec(self::$ch);
		}
	static function sendLocation($chat_id, $loc_long, $loc_lat)
		{
			self::sendAction('find_location',$chat_id);
			$mass['chat_id'] = $chat_id;
			$mass['longitude'] = $loc_long;
			$mass['latitude'] = $loc_lat;
			curl_setopt(self::$ch, CURLOPT_URL, API_URL .'sendLocation');
			curl_setopt(self::$ch, CURLOPT_POSTFIELDS, http_build_query($mass));
			return curl_exec(self::$ch);
		}
	static function sendVenue($chat_id, $loc_long, $loc_lat, $title, $text)
		{
			self::sendAction('find_location',$chat_id);
			$mass['chat_id'] = $chat_id;
			$mass['longitude'] = $loc_long;
			$mass['latitude'] = $loc_lat;
			$mass['title'] = $title;
			$mass['address'] = $text;
			curl_setopt(self::$ch, CURLOPT_URL, API_URL .'sendVenue');
			curl_setopt(self::$ch, CURLOPT_POSTFIELDS, http_build_query($mass));
			return curl_exec(self::$ch);
		}
	static function sendVideo($chat_id, $video, $msg)
		{
			self::sendAction('upload_video',$chat_id);
			$mass['chat_id'] = $chat_id;
			$mass['caption'] = $msg;
			$mass['video'] = $video;
			curl_setopt(self::$ch, CURLOPT_URL, API_URL .'sendVideo');
			curl_setopt(self::$ch, CURLOPT_POSTFIELDS, http_build_query($mass));
			return curl_exec(self::$ch);
		}
	static function sendDocument($chat_id, $doc)
		{
			self::sendAction('upload_document',$chat_id);
			$mass['chat_id'] = $chat_id;
			//$mass['caption'] = $msg;
			$mass['document'] = $doc;
			curl_setopt(self::$ch, CURLOPT_URL, API_URL .'sendDocument');
			curl_setopt(self::$ch, CURLOPT_POSTFIELDS, http_build_query($mass));
			return curl_exec(self::$ch);
		}
	static function sendContact($chat_id, $num, $first, $last)
		{
			self::sendAction('upload_document',$chat_id);
			
			$keys['hide_keyboard'] = true;
			$keys['selective'] = true;
			$mass['reply_markup'] = json_encode($keys);
			
			$mass['chat_id'] = $chat_id;
			//$mass['caption'] = $msg;
			$mass['phone_number'] = $num;
			$mass['first_name'] = $first;
			if($last)
				$mass['last_name'] = $last;
			curl_setopt(self::$ch, CURLOPT_URL, API_URL .'sendContact');
			curl_setopt(self::$ch, CURLOPT_POSTFIELDS, http_build_query($mass));
			return curl_exec(self::$ch);
		}
	static function forwardMessage($chat_id, $from_id, $msg_id)
		{
			//self::sendAction('upload_document',$chat_id);
			$mass['chat_id'] = $chat_id;
			$mass['from_chat_id'] = $from_id;
			$mass['message_id'] = $msg_id;
			curl_setopt(self::$ch, CURLOPT_URL, API_URL .'forwardMessage');
			curl_setopt(self::$ch, CURLOPT_POSTFIELDS, http_build_query($mass));
			return curl_exec(self::$ch);
		}
	static function getPhoto($user_id)
		{
			$mass['user_id'] = $user_id;
			//$mass['caption'] = $msg;
			//$mass['voice'] = $voice;
			curl_setopt(self::$ch, CURLOPT_URL, API_URL .'getUserProfilePhotos');
			curl_setopt(self::$ch, CURLOPT_POSTFIELDS, http_build_query($mass));
			return curl_exec(self::$ch);
		}
	static function getFile($file_id)
		{
			$mass['file_id'] = $file_id;
			//$mass['caption'] = $msg;
			//$mass['voice'] = $voice;
			curl_setopt(self::$ch, CURLOPT_URL, API_URL .'getFile');
			curl_setopt(self::$ch, CURLOPT_POSTFIELDS, http_build_query($mass));
			return curl_exec(self::$ch);
		}
	static function getAdm($c_id)
		{
			$mass['chat_id'] = $c_id;
			curl_setopt(self::$ch, CURLOPT_URL, API_URL .'getChatAdministrators');
			curl_setopt(self::$ch, CURLOPT_POSTFIELDS, http_build_query($mass));
			return curl_exec(self::$ch);
		}
	static function leave($chat_id)
		{
			$mass['chat_id'] = $chat_id;
			curl_setopt(self::$ch, CURLOPT_URL, API_URL .'leaveChat');
			curl_setopt(self::$ch, CURLOPT_POSTFIELDS, http_build_query($mass));
			return curl_exec(self::$ch);
		}
	static function getUser($c_id, $u_id)
		{
			$mass['chat_id'] = $c_id;
			$mass['user_id'] = $u_id;
			curl_setopt(self::$ch, CURLOPT_URL, API_URL .'getChatMember');
			curl_setopt(self::$ch, CURLOPT_POSTFIELDS, http_build_query($mass));
			return curl_exec(self::$ch);
		}
	static function getUserCount($c_id)
		{
			$mass['chat_id'] = $c_id;
			curl_setopt(self::$ch, CURLOPT_URL, API_URL .'getChatMembersCount');
			curl_setopt(self::$ch, CURLOPT_POSTFIELDS, http_build_query($mass));
			return curl_exec(self::$ch);
		}
	static function stop()
		{
			curl_close(self::$ch);
		}
}
?>