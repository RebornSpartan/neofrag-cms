<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\User\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Index extends Controller_Module
{
	public function edit()
	{
		$this	->title($this->lang('Gérer mon compte'))
				->icon('fa-cogs')
				->breadcrumb();

		$this->form()
			->add_rules('user', [
				'username'      => $this->user->username,
				'email'         => $this->user->email,
				'first_name'    => $this->user->profile()->first_name,
				'last_name'     => $this->user->profile()->last_name,
				'avatar'        => $this->user->profile()->avatar->id,
				'signature'     => $this->user->profile()->signature,
				'date_of_birth' => $this->user->profile()->date_of_birth,
				'sex'           => $this->user->profile()->sex,
				'location'      => $this->user->profile()->location,
				'website'       => $this->user->profile()->website,
				'quote'         => $this->user->profile()->quote
			])
			->add_submit($this->lang('Valider'))
			->add_back('user');

		if ($this->form()->is_valid($post))
		{
			$this->model()->edit_user(	$post['username'],
										$post['email'],
										$post['first_name'],
										$post['last_name'],
										$post['avatar'],
										$post['date_of_birth'],
										$post['sex'],
										$post['location'],
										$post['website'],
										$post['quote'],
										$post['signature']);

			if ($post['password_new'] && $post['password_new'] != $post['password_old'])
			{
				$this->model()->update_password($post['password_new']);

				$this->db	->where('user_id', $this->user->id)
							->where('id <>', $this->session->id)
							->delete('nf_session');
			}

			redirect_back('user/'.$this->user->id.'/'.url_title($this->user->username));
		}

		return $this->row(
			$this->col(
				$this->_panel_profile(),
				$this->panel()->body($this->view('menu'), FALSE)
			),
			$this->col(
				$this	->panel()
						->heading()
						->body($this->form()->display())
						->size('col-md-8 col-lg-9')
					)
		);
	}

	public function sessions($sessions)
	{
		$this	->title('Gérer mes sessions')
				->icon('fa-globe')
				->breadcrumb();

		$active_sessions = $this->table()
			->add_columns([
				[
					'content' => function($data){
						return $data['remember_me'] ? '<i class="fa fa-toggle-on text-green" data-toggle="tooltip" title="'.$this->lang('Connexion persistante').'"></i>' : '<i class="fa fa-toggle-off text-grey" data-toggle="tooltip" title="'.$this->lang('Connexion non persistante').'"></i>';
					},
					'size'    => TRUE,
					'align'   => 'center'
				],
				[
					'content' => function($data){
						return user_agent($data['user_agent']);
					},
					'size'    => TRUE,
					'align'   => 'center'
				],
				[
					'title'   => $this->lang('Adresse IP'),
					'content' => function($data){
						return geolocalisation($data['ip_address']).'<span data-toggle="tooltip" data-original-title="'.$data['host_name'].'">'.$data['ip_address'].'</span>';
					}
				],
				[
					'title'   => $this->lang('Site référent'),
					'content' => function($data){
						return $data['referer'] ? urltolink($data['referer']) : $this->lang('Aucun');
					}
				],
				[
					'title'   => $this->lang('Date d\'arrivée'),
					'content' => function($data){
						return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag()->lang('%A %e %B %Y, %H:%M'), $data['date']).'">'.time_span($data['date']).'</span>';
					}
				],
				[
					'title'   => $this->lang('Dernière activité'),
					'content' => function($data){
						return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag()->lang('%A %e %B %Y, %H:%M'), $data['last_activity']).'">'.time_span($data['last_activity']).'</span>';
					}
				],
				[
					'content' => [function($data){
						if ($data['session_id'] != NeoFrag()->session->id)
						{
							return $this->button_delete('user/sessions/delete/'.$data['session_id']);
						}
					}]
				]
			])
			->pagination(FALSE)
			->data($this->user->get_sessions())
			->save();

		$sessions_history = $this->table()
			->add_columns([
				[
					'content' => function($data){
						return user_agent($data['user_agent']);
					},
					'size'    => TRUE,
					'align'   => 'center'
				],
				[
					'title'   => $this->lang('Adresse IP'),
					'content' => function($data){
						return geolocalisation($data['ip_address']).'<span data-toggle="tooltip" data-original-title="'.$data['host_name'].'">'.$data['ip_address'].'</span>';
					}
				],
				[
					'title'   => $this->lang('Site référent'),
					'content' => function($data){
						return $data['referer'] ? urltolink($data['referer']) : $this->lang('Aucun');
					}
				],
				[
					'title'   => $this->lang('Date d\'arrivée'),
					'content' => function($data){
						return '<span data-toggle="tooltip" title="'.timetostr(NeoFrag()->lang('%A %e %B %Y, %H:%M'), $data['date']).'">'.time_span($data['date']).'</span>';
					}
				]
			])
			->data($sessions)
			->no_data($this->lang('Aucun historique disponible'));

		return $this->row(
			$this->col(
				$this->_panel_profile(),
				$this->panel()->body($this->view('menu'), FALSE)
			),
			$this->col(
				$this	->panel()
						->heading($this->lang('Mes sessions actives'), 'fa-shield')
						->body($active_sessions->display())
						->size('col-md-8 col-lg-9'),
				$this	->panel()
						->heading($this->lang('Historique de mes sessions'), 'fa-power-off')
						->body($sessions_history->display())
			)
		);
	}

	public function _session_delete($session_id)
	{
		$this	->title($this->lang('Confirmation de suppression'))
				->form()
				->confirm_deletion($this->lang('Confirmation de suppression'), $this->lang('Êtes-vous sûr(e) de vouloir supprimer la session de l\'utilisateur <b>%s</b> ?'));

		if ($this->form()->is_valid())
		{
			$this->db	->where('id', $session_id)
						->delete('nf_session');

			return 'OK';
		}

		return $this->form()->display();
	}

	public function auth($authenticator)
	{
		spl_autoload_register(function($name){
			if (preg_match('/^SocialConnect/', $name))
			{
				require_once 'lib/'.str_replace('\\', '/', $name).'.php';
			}
		});

		$service = new \SocialConnect\Auth\Service(
			new \SocialConnect\Common\Http\Client\Curl,
			new \SocialConnect\Provider\Session\NeoFrag($this->session), [
				'redirectUri' => $authenticator->static_url(),
				'provider'    => [
					$name = str_replace('_', '-', $authenticator->info()->name) => $authenticator->config()
				]
			]
		);

		$provider = $service->getProvider($name);

		if ($callback = $authenticator->data($params))
		{
			$data = $callback($provider->getIdentity($provider->getAccessTokenByRequestParameters($params)));

			if (($auth = $this->collection('auth')->where('authenticator', $authenticator->__addon->id)->where('key', $data['id'])->row()) && $auth->key == $data['id'])
			{
				if ($this->user->id != $auth->user->id)
				{
					$auth	->set_if($data['username'], 'username', $data['username'])
							->set_if($data['avatar'],   'avatar',   $data['avatar'])
							->update();

					$this->session->login($auth->user);
				}
			}
			else if ($this->user())
			{
				$auth	->set('user',          $this->user)
						->set('authenticator', $authenticator->__addon)
						->set('key',           $data['id'])
						->set_if($data['username'], 'username', $data['username'])
						->set_if($data['avatar'],   'avatar',   $data['avatar'])
						->create();

				notify($this->lang('Connexion établie via %s', $authenticator->info()->title));
			}
			else
			{
				$this->session->append('auth', 'providers', $authenticator->__addon->id.'-'.$data['id'], [$authenticator->__addon->id, $data]);

				notify($this->lang('Compte %s inconnu', $authenticator->info()->title), 'danger');
			}

			redirect();
		}

		$this->url->redirect($provider->makeAuthUrl());
	}

	public function _auth()
	{
		return 'auth';
	}

	public function lost_password($token)
	{
		$this->session->append('modals', 'ajax/user/lost-password/'.$token->id);
		redirect();
	}

	public function logout()
	{
		$this->session->logout();
		redirect();
	}

	public function _messages($messages, $allow_delete = FALSE)
	{
		$this->breadcrumb();

		return $this->row(
			$this->col(
				$this->_panel_messages()
			),
			$this->col(
				$this	->panel()
						->heading()
						->body(!$messages ? '<h4 class="text-center">Aucun message</h4>' : $this->view('messages/inbox', [
							'messages'     => $messages,
							'allow_delete' => $allow_delete
						]), FALSE)
						->size('col-md-8 col-lg-9'),
				$this->module->pagination->panel()
			)
		);
	}

	public function _messages_inbox($messages)
	{
		return $this	->title('Boîte de réception')
						->icon('fa-inbox')
						->_messages($messages, TRUE);
	}

	public function _messages_sent($messages)
	{
		return $this	->title('Messages envoyés')
						->icon('fa-send-o')
						->_messages($messages);
	}

	public function _messages_archives($messages)
	{
		return $this	->title('Archives')
						->icon('fa-archive')
						->_messages($messages);
	}

	public function _messages_read($message_id, $title, $replies)
	{
		$this	->form()
				->add_rules([
					'message' => [
						'label' => 'Mon message',
						'type'  => 'editor',
						'rules' => 'required'
					]
				])
				->add_submit('Envoyer');

		if ($this->form()->is_valid($post))
		{
			$this->model('messages')->reply($message_id, $post['message']);

			redirect('user/messages/'.$message_id.'/'.url_title($title));
		}

		return $this->row(
			$this->col(
				$this->_panel_messages()
			),
			$this->col(
				$this	->panel()
						->heading($title, 'fa-envelope-o')
						->body($this->view('messages/replies', [
							'replies' => $replies
						]))
						->size('col-md-8 col-lg-9'),
				$this	->panel()
						->heading('Répondre', 'fa-reply')
						->body($this->form()->display())
			)
		);
	}

	public function _messages_compose($username)
	{
		$this	->title('Nouveau message')
				->icon('fa-edit')
				->breadcrumb()
				->form()
				->add_rules([
					'title' => [
						'label' => 'Sujet du message',
						'type'  => 'text',
						'rules' => 'required'
					],
					'recipients' => [
						'label'       => 'Destinataires',
						'value'       => $username,
						'type'        => 'text',
						'rules'       => 'required',
						'description' => 'Séparez plusieurs destinataires par un <b>;</b> <small>(point virgule)</small>'
					],
					'message' => [
						'label' => 'Mon message',
						'type'  => 'editor',
						'rules' => 'required'
					]
				])
				->add_submit('Envoyer');

		if ($this->form()->is_valid($post))
		{
			if ($message_id = $this->model('messages')->insert_message($post['recipients'], $post['title'], $post['message']))
			{
				redirect('user/messages/'.$message_id.'/'.url_title($post['title']));
			}
		}

		return $this->row(
			$this->col(
				$this->_panel_messages()
			),
			$this->col(
				$this	->panel()
						->heading()
						->body($this->form()->display())
						->size('col-md-8 col-lg-9')
			)
		);
	}

	public function _messages_delete($message_id, $title)
	{
		$this	->title($this->lang('Suppression du message'))
				->subtitle($title)
				->form()
				->confirm_deletion($this->lang('Confirmation de suppression'), 'Êtes-vous sûr(e) de vouloir supprimer le message <b>'.$title.'</b> ?');

		if ($this->form()->is_valid())
		{
			$this->db	->where('user_id', $this->user->id)
						->where('message_id', $message_id)
						->update('nf_users_messages_recipients', [
							'date'    => now(),
							'deleted' => TRUE
						]);

			return 'OK';
		}

		return $this->form()->display();
	}

	public function _member($user_id, $username)
	{
		$this->title($username);

		return $this->array
					->append($this	->panel()
									->heading($username, 'fa-user')
									->body($this->view('profile_public', $this->model()->get_user_profile($user_id)))
					)
					->append($this->panel_back($this->module('members') ? 'members' : ''));
	}

	public function _panel_profile(&$user_profile = NULL)
	{
		$this->css('profile');

		return $this->panel()
					->heading('Mon profil', 'fa-user')
					->body($this->view('profile', $user_profile = $this->model()->get_user_profile($this->user->id)))
					->size('col-md-4 col-lg-3');
	}

	public function _panel_infos($user_id = NULL)
	{
		if ($user_id === NULL)
		{
			$user_id = $this->user->id;

			$infos = [
				'registration_date'  => $this->user->registration_date,
				'last_activity_date' => $this->user->last_activity_date
			];
		}
		else
		{
			$infos = $this->db	->select('registration_date', 'last_activity_date')
								->from('nf_user')
								->where('id', $user_id)
								->where('deleted', FALSE)
								->row();
		}

		$infos['groups'] = $this->groups->user_groups($user_id);

		return $this->panel()
					->body($this->view('infos', $infos))
					->size('col-md-8 col-lg-9');
	}

	public function _panel_activities($user_id = NULL)
	{
		$this->css('activities');

		if ($user_id === NULL)
		{
			$user_id = $this->user->id;
		}

		$user_activity = [];

		//TODO
		if ($forum = $this->module('forum'))
		{
			$categories = array_filter($this->db->select('category_id')->from('nf_forum_categories')->get(), function($a){
				return $this->access('forum', 'category_read', $a);
			});

			$user_activity = $this->db	->select('m.message_id', 'm.topic_id', 't.title', 'u.id as user_id', 'u.username', 'up.avatar', 'up.signature', 'up.sex', 'u.admin', 'm.message', 'UNIX_TIMESTAMP(m.date) as date')
										->from('nf_forum_messages m')
										->join('nf_forum_topics   t',  'm.topic_id  = t.topic_id')
										->join('nf_forum          f',  't.forum_id  = f.forum_id')
										->join('nf_forum          f2', 'f.parent_id = f2.forum_id AND f.is_subforum = "1"')
										->join('nf_user           u',  'm.user_id   = u.id AND u.deleted = "0"')
										->join('nf_user_profile   up', 'u.id        = up.user_id')
										->where('m.user_id', $user_id)
										->where('IFNULL(f2.parent_id, f.parent_id)', $categories)
										->order_by('m.date DESC')
										->limit(10)
										->get();
		}

		return $this->panel()
					->heading('Activité récente')
					->body($this->view('activity', [
						'user_activity' => $user_activity
					]));
	}

	private function _panel_messages()
	{
		return $this->panel()
					->heading('Messagerie privée', 'fa-envelope-o')
					->body($this->view('messages/menu'), FALSE)
					->footer('<a href="'.url('user').'">'.icon('fa-arrow-circle-o-left').' Retour sur mon espace</a>', 'left')
					->size('col-md-4 col-lg-3');
	}
}
