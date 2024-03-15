import './App.scss';

// Import Components

import Users from './components/Users/Users';
import UserForm from './components/UserForm/UserForm';
import ModalMessaege from './components/ModalMessage/ModalMessage';

import { useGlobalContext } from './context.js';

function App() {

  const { isOpenModal, modalMsg, setIsOpenModal } = useGlobalContext();

  return (
    <>
      {isOpenModal && <ModalMessaege title={modalMsg} action={()=>{setIsOpenModal(false)}} />}
      <UserForm/>
      <Users/>
    </>
  );
}

export default App;
