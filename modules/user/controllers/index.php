<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\Modules\User\Controllers;

use NF\NeoFrag\Loadables\Controllers\Module as Controller_Module;

class Index extends Controller_Module
{
	public function account($sessions)
	{
<<<<<<< HEAD
		return $this->title('Connexion')
					->icon('fa-sign-in')
					->breadcrumb()
					->array
					->append($this	->row()
									->append(
										$this	->col()
												->size('col-6')
												->append(
													$this	->form2('username current_password new_password email', $this->user)
															->success(function($data){

															})
															->submit('Modifier')
															->panel()
															->title('Info de connexion')
												)
									)
									->append(
										$this	->col()
												->size('col-6')
												->append(
													$this	->form2()
															->rule($this->form_checkbox('delete')
																		->data([
																			'account'   => 'Je souhaite supprimer mon compte',
																			//'keep_data' => 'J\'accepte que mes contributions soient conservées de façon anonyme'
																		])
															)
															->form('current_password')
															->success(function($data){
																if (in_array('account', $data['delete']))
																{
																	//TODO
																	if (1 || in_array('keep_data', $data['delete']))
																	{
																		$this->user->set('deleted', TRUE)->update();
																	}
																	else
																	{
																		$this->user->delete();
																	}

																	NeoFrag()->collection('session')->where('user_id', $this->user->id)->update([
																		'user_id' => NULL
																	]);

																	notify('Compte supprimé');

																	redirect();
																}
															})
															->submit('Supprimer', 'danger')
															->panel()
															->title('Supprimer mon compte', 'fa-times')
												)
									)
					)
					->append($this	->table2($sessions)
									->col(function($session){
										return user_agent($session->data->session->user_agent);
									})
									->col('Adresse IP', function($session){
										return geolocalisation($ip_address = $session->data->session->ip_address).'<span data-toggle="tooltip" data-original-title="'.$session->data->session->host_name.'">'.$ip_address.'</span>';
									})
									->col('Site référent', function($session){
										return $session->data->session->referer ? urltolink($session->data->session->referer) : $this->lang('Aucun');
									})
									->col('Date', function($session){
										return $session->data->session->date;
									})
									->col('Compte tiers', function($session){
										return $session->auth ? $session->auth : '';
									})
									->delete()
									->panel()
									->title('Sessions actives', 'fa-globe')
					);
	}

	public function profile()
	{
=======
		return $this->title('Mon activité')
					->icon('fa-star-o')
					->array([
						$this->row($this->col($this->panel()->body($this->_panel_infos()))),
						$this	->row()
								->append($this->col($this	->panel()
															->heading('Mon profil')
															->body($this->user->view('user/profile'))
															->size('col-md-5')
								))
								->append($this	->col()
												->size('col-md-7')
												->append($this	->panel()
																->heading('Messagerie')
																->body($this->view('index'))
												)
												->append($this->_panel_activities())
								)
					]);
	}

	public function account($sessions)
	{
		return $this->title('Connexion')
					->icon('fa-sign-in')
					->breadcrumb()
					->array
					->append($this	->row()
									->append(
										$this	->col()
												->size('col-6')
												->append(
													$this	->form2('username current_password new_password email', $this->user)
															->success(function($data){

															})
															->submit('Modifier')
															->panel()
															->title('Info de connexion')
												)
									)
									->append(
										$this	->col()
												->size('col-6')
												->append(
													$this	->form2()
															->rule($this->form_checkbox('delete')
																		->data([
																			'account'   => 'Je souhaite supprimer mon compte',
																			//'keep_data' => 'J\'accepte que mes contributions soient conservées de façon anonyme'
																		])
															)
															->form('current_password')
															->success(function($data){
																if (in_array('account', $data['delete']))
																{
																	//TODO
																	if (1 || in_array('keep_data', $data['delete']))
																	{
																		$this->user->set('deleted', TRUE)->update();
																	}
																	else
																	{
																		$this->user->delete();
																	}

																	NeoFrag()->collection('session')->where('user_id', $this->user->id)->update([
																		'user_id' => NULL
																	]);

																	notify('Compte supprimé');

																	redirect();
																}
															})
															->submit('Supprimer', 'danger')
															->panel()
															->title('Supprimer mon compte', 'fa-times')
												)
									)
					)
					->append($this	->table2($sessions)
									->col(function($session){
										return user_agent($session->data->session->user_agent);
									})
									->col('Adresse IP', function($session){
										return geolocalisation($ip_address = $session->data->session->ip_address).'<span data-toggle="tooltip" data-original-title="'.$session->data->session->host_name.'">'.$ip_address.'</span>';
									})
									->col('Site référent', function($session){
										return $session->data->session->referer ? urltolink($session->data->session->referer) : $this->lang('Aucun');
									})
									->col('Date', function($session){
										return $session->data->session->date;
									})
									->col('Compte tiers', function($session){
										return $session->auth ? $session->auth : '';
									})
									->delete()
									->panel()
									->title('Sessions actives', 'fa-globe')
					);
	}

	public function profile()
	{
>>>>>>> upstream/dev
		return $this->title('Profil')
					->icon('fa-pencil')
					->breadcrumb()
					->form2('profile', $this->user->profile())
					->panel();
	}

	public function sessions($sessions)
	{
		return $this->title('Historique des sessions')
					->icon('fa-history')
					->breadcrumb()
					->table2($sessions, 'Aucun historique')
					->col(function($a){
						return user_agent($a->user_agent);
					})
					->col('Adresse IP', function($a){
						return geolocalisation($ip_address = $a->ip_address).'<span data-toggle="tooltip" data-original-title="'.$a->host_name.'">'.$ip_address.'</span>';
					})
					->col('Site référent', function($a){
						return $a->referer ? urltolink($a->referer) : $this->lang('Aucun');
					})
					->col('Date', 'date')
					->col('Compte tiers', function($a){
						return $a->auth ? $a->auth : '';
					})
					->panel();
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
<<<<<<< HEAD

		$provider = $service->getProvider($name);

		if ($callback = $authenticator->data($params))
		{
			$data = $callback($provider->getIdentity($provider->getAccessTokenByRequestParameters($params)));

=======

		$provider = $service->getProvider($name);

		if ($callback = $authenticator->data($params))
		{
			$data = $callback($provider->getIdentity($provider->getAccessTokenByRequestParameters($params)));

>>>>>>> upstream/dev
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

	public function _auth($auths)
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

		return $this->col(
			$this	->panel()
					->heading()
					->body(!$messages ? '<h4 class="text-center">Aucun message</h4>' : $this->view('messages/inbox', [
						'messages'     => $messages,
						'allow_delete' => $allow_delete
					]), FALSE),
			$this->module->pagination->panel()
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

<<<<<<< HEAD
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
=======
		return $this->col(
			$this	->panel()
					->heading($title, 'fa-envelope-o')
					->body($this->view('messages/replies', [
						'replies' => $replies
					])),
			$this	->panel()
					->heading('Répondre', 'fa-reply')
					->body($this->form()->display())
>>>>>>> upstream/dev
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

<<<<<<< HEAD
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
=======
		return $this->panel()
					->heading()
					->body($this->form()->display());
>>>>>>> upstream/dev
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

	public function _member($user)
	{
		$this->output->data->set('pre_module', $this->array
													->append($user->view('user/cover'))
													->append('	<div class="user-info">
																	<div class="container">
																		'.$this->row($this->col($this->_panel_infos($user))->size('col-8 offset-4')).'
																	</div>
																</div>')
		);

<<<<<<< HEAD
		return $this->array
					->append($this	->panel()
									->heading($username, 'fa-user')
									->body($this->view('profile_public', $this->model()->get_user_profile($user_id)))
					)
					->append($this->panel_back($this->module('members') ? 'members' : ''));
=======
		return $this->title($user->username)
					->breadcrumb('Profil')
					->breadcrumb($user->username)
					->row()
					->append($this	->col()
									->size('col-4 user-col')
									->append($this	->panel()
													->body($user->view('user/profile'))
									)
									->append_if(in_array('donate-0', $this->groups($user->id)), function(){
										return $this->panel()
													->style('honor')
													->body('<h4>'.icon('fa-usd text-success').' Donateur</h4>');
									})
									->append_if(in_array('shop2-0', $this->groups($user->id)), function(){
										return $this->panel()
													->style('honor')
													->body('<h4>'.icon('fa-cubes text-warning').' Contributeur</h4>');
									})
					)
					->append($this	->col()
									->size('col-8')
									->append($this->_panel_activities())
									->append($this->panel_back())
					);
>>>>>>> upstream/dev
	}

	public function _panel_profile(&$user_profile = NULL)
	{
		$this->css('profile');

		return $this->panel()
					->heading('Mon profil', 'fa-user')
					->body($this->view('profile', $user_profile = $this->model()->get_user_profile($this->user->id)))
					->size('col-md-4 col-lg-3');
	}

	public function _panel_infos($user = NULL)
	{
<<<<<<< HEAD
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
=======
		return $this->view('infos', [
			'user' => $user ?: $this->user
		]);
>>>>>>> upstream/dev
	}

	public function _panel_activities($user_id = NULL)
	{
		$this	->css('activities')
				->js('user')
				->css('jquery.mCustomScrollbar.min')
				->js('jquery.mCustomScrollbar.min');

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
}
