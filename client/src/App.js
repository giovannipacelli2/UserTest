import './App.scss';

// Import Components

import UserList from './components/UserList/UserList';
import ModalMessaege from './components/ModalMessage/ModalMessage';

import { useGlobalContext } from './context.js';

function App() {

  const { isOpenModal, modalMsg, setIsOpenModal } = useGlobalContext();

  return (
    <>
      {isOpenModal && <ModalMessaege title={modalMsg} action={()=>{setIsOpenModal(false)}} />}
      <UserList/>
    </>
  );
}

export default App;
